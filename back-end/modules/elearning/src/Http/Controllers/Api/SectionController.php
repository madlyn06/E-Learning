<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Elearning\Http\Resources\SectionResource;
use Modules\Elearning\Models\Section;

class SectionController extends BaseController
{
    public function index($courseId): JsonResponse
    {
        try {
            $sections = Section::where('course_id', $courseId)->with('lessons')->get();
            return $this->successResponse(SectionResource::collection($sections), 'Sections retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve sections: ' . $e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $section = Section::find($id);
            if (!$section) {
                return $this->notFoundResponse('Section not found');
            }
            return $this->successResponse(new SectionResource($section), 'Section retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve section: ' . $e->getMessage());
        }
    }

    public function store(\Modules\Elearning\Http\Requests\Section\StoreSectionRequest $request, $courseId): JsonResponse
    {
        $validated = $request->validated();
        $validated['course_id'] = $courseId;
        $section = Section::create($validated);
        return $this->createdResponse(new SectionResource($section), 'Section created successfully');
    }

    public function update(\Modules\Elearning\Http\Requests\Section\StoreSectionRequest $request, $id): JsonResponse
    {
        $section = Section::find($id);
        if (!$section) {
            return $this->notFoundResponse('Section not found');
        }
        $section->update($request->validated());
        return $this->successResponse(new SectionResource($section), 'Section updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $section = Section::find($id);
        if (!$section) {
            return $this->notFoundResponse('Section not found');
        }
        $section->delete();
        return $this->successResponse(null, 'Section deleted successfully');
    }
}
