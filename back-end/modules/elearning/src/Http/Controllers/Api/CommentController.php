<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Requests\Comment\StoreCommentRequest;
use Modules\Elearning\Http\Resources\CommentResource;
use Modules\Elearning\Models\Comment;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function index(Request $request, $lessonId): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $comments = Comment::where('lesson_id', $lessonId)
            ->whereNull('parent_id')  // Only get top-level comments
            ->with(['user', 'children.user'])  // Load replies and users
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json(CommentResource::collection($comments));
    }

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        
        $comment = Comment::create($validated);
        
        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }

    public function update(StoreCommentRequest $request, $id): JsonResponse
    {
        $comment = Comment::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();
            
        if (!$comment) {
            return response()->json(['message' => 'Comment not found or unauthorized'], Response::HTTP_NOT_FOUND);
        }
        
        $validated = $request->validated();
        $comment->update($validated);
        
        return response()->json(new CommentResource($comment));
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $comment = Comment::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();
            
        if (!$comment) {
            return response()->json(['message' => 'Comment not found or unauthorized'], Response::HTTP_NOT_FOUND);
        }
        
        $comment->delete();
        
        return response()->json(['message' => 'Comment deleted']);
    }
    
    public function like($id): JsonResponse
    {
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }
        
        $comment->increment('like');
        
        return response()->json(['like' => $comment->like]);
    }
    
    public function dislike($id): JsonResponse
    {
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }
        
        $comment->increment('dislike');
        
        return response()->json(['dislike' => $comment->dislike]);
    }
}
