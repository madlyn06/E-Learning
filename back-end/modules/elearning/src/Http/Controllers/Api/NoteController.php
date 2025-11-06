<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Requests\Note\StoreNoteRequest;
use Modules\Elearning\Http\Resources\NoteResource;
use Modules\Elearning\Models\Note;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function index(Request $request, $lessonId): JsonResponse
    {
        $userId = $request->user()->id;
        $perPage = $request->input('per_page', 15);
        $notes = Note::where('lesson_id', $lessonId)
            ->where('user_id', $userId)
            ->orderBy('time_seconds')
            ->paginate($perPage);
        
        return response()->json(NoteResource::collection($notes));
    }

    public function store(StoreNoteRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $validated = $request->validated();
        $validated['user_id'] = $userId;
        
        $note = Note::create($validated);
        
        return response()->json(new NoteResource($note), Response::HTTP_CREATED);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $userId = $request->user()->id;
        $note = Note::where('id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json(new NoteResource($note));
    }

    public function update(StoreNoteRequest $request, $id): JsonResponse
    {
        $userId = $request->user()->id;
        $note = Note::where('id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }
        
        $validated = $request->validated();
        $note->update($validated);
        
        return response()->json(new NoteResource($note));
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $userId = $request->user()->id;
        $note = Note::where('id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }
        
        $note->delete();
        
        return response()->json(['message' => 'Note deleted']);
    }
}
