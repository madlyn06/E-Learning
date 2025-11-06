<?php

namespace Modules\Elearning\Contracts;

interface VideoDriverInterface
{
    /**
     * Upload a video file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $options
     * @return array
     */
    public function upload($file, array $options = []): array;

    /**
     * Upload video in chunks
     *
     * @param string $chunkPath
     * @param string $fileName
     * @param int $chunkNumber
     * @param int $totalChunks
     * @param array $options
     * @return array
     */
    public function uploadChunk(string $chunkPath, string $fileName, int $chunkNumber, int $totalChunks, array $options = []): array;

    /**
     * Delete a video file
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool;

    /**
     * Get video URL for streaming
     *
     * @param string $path
     * @param array $options
     * @return string
     */
    public function getStreamingUrl(string $path, array $options = []): string;

    /**
     * Get video URL for download
     *
     * @param string $path
     * @param array $options
     * @return string
     */
    public function getDownloadUrl(string $path, array $options = []): string;

    /**
     * Check if video exists
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Get video metadata
     *
     * @param string $path
     * @return array
     */
    public function getMetadata(string $path): array;

    /**
     * Generate thumbnail
     *
     * @param string $path
     * @param string $time
     * @param array $options
     * @return string
     */
    public function generateThumbnail(string $path, string $time = '00:00:05', array $options = []): string;

    /**
     * Convert video to HLS
     *
     * @param string $path
     * @param array $options
     * @return array
     */
    public function convertToHls(string $path, array $options = []): array;

    /**
     * Get upload progress
     *
     * @param string $uploadId
     * @return array
     */
    public function getUploadProgress(string $uploadId): array;

    /**
     * Get driver configuration
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Validate file before upload
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function validateFile($file): bool;
}
