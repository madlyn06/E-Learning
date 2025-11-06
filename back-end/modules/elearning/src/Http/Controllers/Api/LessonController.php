<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Http\Requests\Lesson\StoreLessonRequest;
use Modules\Elearning\Http\Requests\Lesson\UpdateLessonRequest;
use Modules\Elearning\Http\Resources\LessonResource;
use Modules\Elearning\Models\Lesson;
use Modules\Elearning\Models\Section;
use Modules\Elearning\Services\LessonService;
use Newnet\Media\MediaUploader;

class LessonController extends BaseController
{
    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * Get lessons for a section
     */
    public function index($sectionId): JsonResponse
    {
        try {
            $lessons = Lesson::where('section_id', $sectionId)
                ->where('is_enabled', true)
                ->orderBy('display_order', 'asc')->get();

            return $this->successResponse(
                LessonResource::collection($lessons),
                'Lessons retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve lessons: ' . $e->getMessage());
        }
    }

    /**
     * Get lesson details
     */
    public function show($id): JsonResponse
    {
        try {
            $lesson = $this->findLessonWithRelations($id);

            return $this->successResponse(
                new LessonResource($lesson),
                'Lesson retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve lesson: ' . $e->getMessage());
        }
    }

    /**
     * Create a new lesson
     */
    public function store(StoreLessonRequest $request, $sectionId): JsonResponse
    {
        try {
            $this->validateSectionAccess($sectionId);
            $user = $this->getAuthenticatedUser();

            $lessonData = $this->lessonService->prepareLessonData($request, $sectionId, $user->id);
            $lesson = $this->lessonService->createLesson($lessonData);

            // Handle file uploads based on lesson type
            $this->handleLessonFileUploads($lesson, $request);

            return $this->createdResponse(
                new LessonResource($lesson),
                'Lesson created successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create lesson: ' . $e->getMessage());
        }
    }

    /**
     * Update lesson
     */
    public function update(UpdateLessonRequest $request, $id): JsonResponse
    {
        try {
            $lesson = $this->findLessonWithRelations($id);
            $this->validateLessonOwnership($lesson);
            $updatedLesson = $this->lessonService->updateLesson($lesson, $request->validated());

            return $this->updatedResponse(
                new LessonResource($updatedLesson),
                'Lesson updated successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update lesson: ' . $e->getMessage());
        }
    }

    /**
     * Delete lesson
     */
    public function destroy($id): JsonResponse
    {
        try {
            $lesson = $this->findLessonWithRelations($id);
            $this->validateLessonOwnership($lesson);

            $this->lessonService->deleteLesson($lesson);

            return $this->deletedResponse('Lesson deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to delete lesson: ' . $e->getMessage());
        }
    }

    /**
     * Get HLS progress for video lesson
     */
    public function hlsProgress($lessonId): JsonResponse
    {
        try {
            $lesson = $this->findLesson($lessonId);

            if ($lesson->type !== 'video') {
                return $this->badRequestResponse('HLS progress is only available for video lessons');
            }

            $progress = $this->lessonService->getHlsProgress($lessonId);

            return $this->successResponse(
                ['progress' => $progress],
                'HLS progress retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve HLS progress: ' . $e->getMessage());
        }
    }

    /**
     * Get video streaming URLs with network-aware quality selection
     */
    public function getVideoStreamingUrls(Request $request, $lessonId): JsonResponse
    {
        try {
            $lesson = $this->findLesson($lessonId);

            if ($lesson->type !== 'video') {
                return $this->badRequestResponse('Video streaming is only available for video lessons');
            }

            // Get network type from request or detect automatically
            $networkType = $request->input('network_type', 'auto');
            $userAgent = $request->header('User-Agent');
            $detectedNetwork = $this->detectNetworkType($userAgent, $networkType);

            // Get recommended quality based on network
            $recommendedQuality = $lesson->getRecommendedQuality($detectedNetwork);

            // Get all available qualities
            $availableQualities = $lesson->getAvailableHlsQualities();

            $response = [
                'lesson_id' => $lesson->id,
                'lesson_name' => $lesson->name,
                'network_detected' => $detectedNetwork,
                'recommended_quality' => $recommendedQuality,
                'available_qualities' => $availableQualities,
                'streaming_data' => $lesson->video,
                'auto_adaptation' => [
                    'enabled' => true,
                    'algorithm' => 'network_aware',
                    'fallback_strategy' => 'progressive_downgrade',
                    'buffer_threshold' => 5, // seconds
                    'quality_switch_threshold' => 0.8, // 80% buffer
                ],
                'metadata' => [
                    'duration' => $lesson->duration_minutes,
                    'file_size' => $lesson->size,
                    'original_format' => $lesson->extension,
                    'hls_conversion_status' => $lesson->isHlsConverted() ? 'completed' : 'pending',
                    'last_updated' => $lesson->updated_at
                ]
            ];

            return $this->successResponse(
                $response,
                'Video streaming URLs retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve video streaming URLs: ' . $e->getMessage());
        }
    }

    /**
     * Detect network type based on User-Agent and request parameters
     */
    private function detectNetworkType(?string $userAgent, string $requestedType): string
    {
        // If user explicitly requests a network type, use it
        if ($requestedType !== 'auto') {
            return $requestedType;
        }

        // Auto-detect based on User-Agent patterns
        if (!$userAgent) {
            return 'auto';
        }

        $userAgent = strtolower($userAgent);

        // Mobile network detection
        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false || strpos($userAgent, 'iphone') !== false) {
            // Check for specific mobile indicators
            if (strpos($userAgent, 'opera mini') !== false || strpos($userAgent, 'ucbrowser') !== false) {
                return 'slow'; // Likely 2G/3G
            }

            if (strpos($userAgent, 'chrome') !== false || strpos($userAgent, 'safari') !== false) {
                return 'medium'; // Likely 4G
            }

            return 'medium'; // Default to medium for mobile
        }

        // Desktop/tablet detection
        if (strpos($userAgent, 'windows') !== false || strpos($userAgent, 'macintosh') !== false || strpos($userAgent, 'linux') !== false) {
            return 'fast'; // Likely WiFi/5G
        }

        return 'auto'; // Default fallback
    }

    /**
     * Reorder lessons in a section
     */
    public function reorder(Request $request, $sectionId): JsonResponse
    {
        try {
            $this->validateSectionAccess($sectionId);

            $request->validate([
                'lesson_order' => 'required|array',
                'lesson_order.*' => 'required|integer|exists:elearning__lessons,id'
            ]);

            $this->lessonService->reorderLessons($sectionId, $request->lesson_order);

            return $this->successResponse(null, 'Lessons reordered successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to reorder lessons: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate lesson
     */
    public function duplicate($id): JsonResponse
    {
        try {
            $lesson = $this->findLessonWithRelations($id);
            $this->validateLessonOwnership($lesson);

            $duplicatedLesson = $this->lessonService->duplicateLesson($lesson);

            return $this->createdResponse(
                new LessonResource($duplicatedLesson),
                'Lesson duplicated successfully'
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to duplicate lesson: ' . $e->getMessage());
        }
    }

    /**
     * Validate section access and return section
     */
    private function validateSectionAccess($sectionId): Section
    {
        $section = Section::with('course')->find($sectionId);

        if (!$section) {
            throw new \Exception('Section not found');
        }

        $user = $this->getAuthenticatedUser();
        if ($section->course->user_id !== $user->id) {
            throw new \Exception('You can only access lessons in your own courses');
        }

        return $section;
    }

    /**
     * Find lesson with relations
     */
    private function findLessonWithRelations($id): Lesson
    {
        $lesson = Lesson::with(['section.course', 'questions'])
            ->where('is_enabled', true)
            ->whereId($id)->first();

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        return $lesson;
    }

    /**
     * Find lesson without relations
     */
    private function findLesson($id): Lesson
    {
        $lesson = Lesson::where('is_enabled', true)->whereId($id)->first();

        if (!$lesson) {
            throw new \Exception('Lesson not found');
        }

        return $lesson;
    }

    /**
     * Validate lesson ownership
     */
    private function validateLessonOwnership(Lesson $lesson): void
    {
        $user = $this->getAuthenticatedUser();

        if ($lesson->section->course->user_id !== $user->id) {
            throw new \Exception('You can only modify lessons in your own courses');
        }
    }

    /**
     * Get authenticated user
     */
    private function getAuthenticatedUser()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        return $user;
    }

    /**
     * Handle file uploads based on lesson type
     */
    private function handleLessonFileUploads(Lesson $lesson, StoreLessonRequest $request): void
    {
        $type = $lesson->type;

        switch ($type) {
            case 'video':
                $this->handleVideoUpload($lesson, $request);
                break;
            case 'quiz':
                $this->handleQuizQuestions($lesson, $request);
                break;
            case 'document':
            case 'file':
                $this->handleDocumentUpload($lesson, $request);
                break;
            case 'youtube':
                $this->handleYoutubeId($lesson, $request);
                break;
        }
    }

    /**
     * Handle video upload and HLS conversion
     */
    private function handleVideoUpload(Lesson $lesson, StoreLessonRequest $request): void
    {
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoPath = $videoFile->store('lesson_videos');
            $lesson->video_id = $videoPath;
            // Store video file information
            $lesson->original_name = $videoFile->getClientOriginalName();
            $lesson->size = $videoFile->getSize();
            $lesson->extension = $videoFile->getClientOriginalExtension();
            $lesson->mime_type = $videoFile->getMimeType();
            $lesson->save();
            $this->lessonService->dispatchHlsConversion($lesson->id, $videoPath);
        }
    }

    /**
     * Handle quiz questions creation
     */
    private function handleQuizQuestions(Lesson $lesson, StoreLessonRequest $request): void
    {
        $validated = $request->validated();

        if (isset($validated['questions'])) {
            foreach ($validated['questions'] as $question) {
                $lesson->questions()->create([
                    'question' => $question['question'],
                    'choices' => json_encode($question['choices']),
                    'correct' => $question['correct'],
                    'explanation' => $question['explanation'] ?? null,
                ]);
            }
        }
    }

    /**
     * Handle document upload
     */
    private function handleDocumentUpload(Lesson $lesson, StoreLessonRequest $request): void
    {
        if (!empty($request->file('file'))) {
            // Multiple file upload
            foreach ($request->file('file') as $file) {
                $media = app(MediaUploader::class)->setFile($file)->upload();
                // $lesson->documents()->attach($media->id);
            }
        }
    }

    private function handleYoutubeId(Lesson $lesson, StoreLessonRequest $request): void
    {
        $lesson->video_id = $request->video_id;
        $lesson->type = 'youtube';
        $lesson->save();
    }
}
