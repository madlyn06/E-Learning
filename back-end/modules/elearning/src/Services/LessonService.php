<?php
namespace Modules\Elearning\Services;

use Modules\Elearning\Models\Lesson;
use Modules\Elearning\Repositories\LessonRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Elearning\Jobs\ProcessHlsConversionJob;
use Illuminate\Support\Str;
use Modules\Elearning\Http\Requests\Lesson\StoreLessonRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LessonService
{
    protected $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Prepare lesson data for creation
     */
    public function prepareLessonData(StoreLessonRequest $request, $sectionId, $userId): array
    {
        $validated = $request->validated();
        $validated['section_id'] = $sectionId;
        $validated['user_id'] = $userId;
        
        // Calculate next position
        $validated['display_order'] = Lesson::where('section_id', $sectionId)->max('display_order') + 1;
        $validated['seourl'] = [
            'request_path' => Str::slug($validated['name']),
            'locale' => LaravelLocalization::getCurrentLocale(),
        ];
        $validated['seometa'] = [
            'title' => $validated['name'],
            'description' => $validated['summary'] ?? $validated['name'],
            'keywords' => $validated['keywords'] ?? '',
            'robots' => $validated['robots'] ?? 'index, follow',
            'canonical' => $validated['canonical'] ?? '',
            'og_title' => $validated['og_title'] ?? '',
            'og_description' => $validated['og_description'] ?? '',
            'twitter_title' => $validated['twitter_title'] ?? '',
            'twitter_description' => $validated['twitter_description'] ?? '',
            'og_image' => $validated['og_image'] ?? '',
            'twitter_image' => $validated['twitter_image'] ?? '',
            'locale' => LaravelLocalization::getCurrentLocale(),
        ];
        return $validated;
    }

    /**
     * Create a new lesson
     */
    public function createLesson(array $data): Lesson
    {
        return $this->lessonRepository->create($data);
    }

    /**
     * Update an existing lesson
     */
    public function updateLesson(Lesson $lesson, array $data): Lesson
    {
        return $this->lessonRepository->updateById($data, $lesson->id);
    }

    /**
     * Delete a lesson
     */
    public function deleteLesson(Lesson $lesson): bool
    {
        return $this->lessonRepository->delete($lesson->id);
    }

    /**
     * Reorder lessons in a section
     */
    public function reorderLessons(int $sectionId, array $lessonOrder): void
    {
        foreach ($lessonOrder as $position => $lessonId) {
            $this->lessonRepository->update(
                ['id' => $lessonId, 'section_id' => $sectionId],
                ['display_order' => $position + 1]
            );
        }
    }

    /**
     * Duplicate a lesson
     */
    public function duplicateLesson(Lesson $lesson): Lesson
    {
        $duplicatedData = $lesson->toArray();
        unset($duplicatedData['id'], $duplicatedData['created_at'], $duplicatedData['updated_at']);
        
        // Calculate new display order
        $maxOrder = $this->lessonRepository->getByConditions(['section_id' => $lesson->section_id])->max('display_order');
        $duplicatedData['display_order'] = ($maxOrder ?? 0) + 1;
        $duplicatedData['name'] = $duplicatedData['name'] . ' (Copy)';
        
        $duplicatedLesson = $this->lessonRepository->create($duplicatedData);

        // Duplicate questions if it's a quiz lesson
        if ($lesson->type === 'quiz' && $lesson->questions) {
            foreach ($lesson->questions as $question) {
                $duplicatedLesson->questions()->create([
                    'question' => $question->question,
                    'choices' => $question->choices,
                    'correct' => $question->correct,
                    'explanation' => $question->explanation,
                ]);
            }
        }

        return $duplicatedLesson;
    }

    /**
     * Dispatch HLS conversion job to background queue
     */
    public function dispatchHlsConversion($lessonId, $videoPath): void
    {
        try {
            // Store initial progress
            $progressKey = 'lesson_hls_progress_' . $lessonId;
            Cache::put($progressKey, [
                'progress' => 0,
                'message' => 'Job queued for processing',
                'updated_at' => now()->toISOString()
            ], 3600); // 1 hour TTL
            
            // Dispatch proper background job for HLS conversion
            ProcessHlsConversionJob::dispatch($lessonId, $videoPath);
            
            Log::info("HLS conversion job dispatched for lesson {$lessonId}");
            
        } catch (\Exception $e) {
            Log::error("Failed to dispatch HLS conversion job for lesson {$lessonId}: " . $e->getMessage());
            
            // Update progress to indicate failure
            $progressKey = 'lesson_hls_progress_' . $lessonId;
            Cache::put($progressKey, [
                'progress' => -1,
                'message' => 'Failed to queue job: ' . $e->getMessage(),
                'updated_at' => now()->toISOString()
            ], 3600);
        }
    }

    /**
     * Get HLS conversion progress
     */
    public function getHlsProgress($lessonId)
    {
        $progressKey = 'lesson_hls_progress_' . $lessonId;
        $progressData = Cache::get($progressKey, [
            'progress' => 0,
            'message' => 'No conversion data found',
            'updated_at' => null
        ]);
        
        // Handle legacy progress format (simple integer)
        if (is_numeric($progressData)) {
            $progress = $progressData;
            if ($progress === -1) {
                return [
                    'status' => 'error',
                    'progress' => 0,
                    'message' => 'HLS conversion failed',
                    'updated_at' => null
                ];
            } elseif ($progress === 100) {
                return [
                    'status' => 'completed',
                    'progress' => 100,
                    'message' => 'HLS conversion completed',
                    'updated_at' => null
                ];
            } elseif ($progress > 0) {
                return [
                    'status' => 'processing',
                    'progress' => $progress,
                    'message' => 'HLS conversion in progress',
                    'updated_at' => null
                ];
            } else {
                return [
                    'status' => 'pending',
                    'progress' => 0,
                    'message' => 'HLS conversion pending',
                    'updated_at' => null
                ];
            }
        }
        
        // Handle new progress format (array with metadata)
        $progress = $progressData['progress'] ?? 0;
        $message = $progressData['message'] ?? 'Unknown status';
        $updatedAt = $progressData['updated_at'] ?? null;
        
        if ($progress === -1) {
            return [
                'status' => 'error',
                'progress' => 0,
                'message' => $message,
                'updated_at' => $updatedAt
            ];
        } elseif ($progress === 100) {
            return [
                'status' => 'completed',
                'progress' => 100,
                'message' => $message,
                'updated_at' => $updatedAt
            ];
        } elseif ($progress > 0) {
            return [
                'status' => 'processing',
                'progress' => $progress,
                'message' => $message,
                'updated_at' => $updatedAt
            ];
        } else {
            return [
                'status' => 'pending',
                'progress' => 0,
                'message' => $message,
                'updated_at' => $updatedAt
            ];
        }
    }

    /**
     * Retry failed HLS conversion
     */
    public function retryHlsConversion($lessonId): bool
    {
        try {
            $lesson = $this->lessonRepository->find($lessonId);
            if (!$lesson || !$lesson->video_url) {
                return false;
            }
            
            // Reset progress
            $progressKey = 'lesson_hls_progress_' . $lessonId;
            Cache::forget($progressKey);
            
            // Dispatch new conversion job
            $this->dispatchHlsConversion($lessonId, $lesson->video_url);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to retry HLS conversion for lesson {$lessonId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find lesson by ID
     */
    public function findLesson($id): ?Lesson
    {
        try {
            return $this->lessonRepository->find($id);
        } catch (\Exception $e) {
            Log::error("Failed to find lesson {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Find lesson by slug
     */
    public function findLessonBySlug($slug): ?Lesson
    {
        try {
            $lessons = $this->lessonRepository->getByConditions(['slug' => $slug]);
            return $lessons->first();
        } catch (\Exception $e) {
            Log::error("Failed to find lesson by slug {$slug}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get lessons by section
     */
    public function getLessonsBySection($sectionId, $columns = ['*'])
    {
        try {
            return $this->lessonRepository->getByConditions(['section_id' => $sectionId])
                ->orderBy('display_order')
                ->get($columns);
        } catch (\Exception $e) {
            Log::error("Failed to get lessons for section {$sectionId}: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get lessons by type
     */
    public function getLessonsByType($type, $columns = ['*'])
    {
        try {
            return $this->lessonRepository->getByConditions(['type' => $type])
                ->orderBy('display_order')
                ->get($columns);
        } catch (\Exception $e) {
            Log::error("Failed to get lessons by type {$type}: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get lessons by user
     */
    public function getLessonsByUser($userId, $columns = ['*'])
    {
        try {
            return $this->lessonRepository->getByConditions(['user_id' => $userId])
                ->orderBy('created_at', 'desc')
                ->get($columns);
        } catch (\Exception $e) {
            Log::error("Failed to get lessons for user {$userId}: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Search lessons
     */
    public function searchLessons($query, $columns = ['*'])
    {
        try {
            return $this->lessonRepository->getByConditions([])
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('summary', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                ->orderBy('display_order')
                ->get($columns);
        } catch (\Exception $e) {
            Log::error("Failed to search lessons: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get lesson statistics
     */
    public function getLessonStatistics()
    {
        try {
            $totalLessons = $this->lessonRepository->getAll()->count();
            $videoLessons = $this->lessonRepository->getByConditions(['type' => 'video'])->count();
            $quizLessons = $this->lessonRepository->getByConditions(['type' => 'quiz'])->count();
            $documentLessons = $this->lessonRepository->getByConditions(['type' => 'document'])->count();
            $enabledLessons = $this->lessonRepository->getByConditions(['is_enabled' => true])->count();

            return [
                'total' => $totalLessons,
                'video' => $videoLessons,
                'quiz' => $quizLessons,
                'document' => $documentLessons,
                'enabled' => $enabledLessons,
                'disabled' => $totalLessons - $enabledLessons
            ];
        } catch (\Exception $e) {
            Log::error("Failed to get lesson statistics: " . $e->getMessage());
            return [
                'total' => 0,
                'video' => 0,
                'quiz' => 0,
                'document' => 0,
                'enabled' => 0,
                'disabled' => 0
            ];
        }
    }
}
