<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Lesson;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    public function uploadMedia(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $request->validate([
            'file' => 'required_without_all:video,image|file|mimes:jpg,jpeg,png,gif,webp,mp4,avi,mov,mkv,webm',
            'video' => 'required_without_all:file,image|file|mimes:mp4,avi,mov,mkv,webm',
            'image' => 'required_without_all:file,video|file|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $uploadedFile = $request->file('file') ?? $request->file('video') ?? $request->file('image');
        if (!$uploadedFile) {
            return response()->json(['message' => 'No file provided'], Response::HTTP_BAD_REQUEST);
        }

        $directory = 'elearning_uploads/' . date('Y/m');
        $path = $uploadedFile->store($directory, 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'url' => $url,
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $uploadedFile->getClientMimeType(),
            'size' => $uploadedFile->getSize(),
            'original_name' => $uploadedFile->getClientOriginalName(),
        ], Response::HTTP_CREATED);
    }
}
