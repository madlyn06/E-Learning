<?php

namespace Modules\Elearning\Services\Video;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use Modules\Elearning\Jobs\ProcessHlsConversionJob;

class ServerVideoDriver extends BaseVideoDriver
{
    public function upload($file, array $options = []): array
    {
        try {
            if (!$this->validateFile($file)) {
                return $this->createErrorResponse('File validation failed');
            }

            $options = $this->validateUploadOptions($options);
            $filename = $this->generateFilename($file, $options);
            $path = $this->config['path'] . '/' . $filename;

            // Store file
            $stored = Storage::disk($this->config['disk'])->putFileAs(
                dirname($path),
                $file,
                basename($path),
                $options
            );

            if (!$stored) {
                return $this->createErrorResponse('Failed to store file on server');
            }

            $metadata = $this->getFileMetadata($file);
            $response = $this->createUploadResponse($path, $metadata);

            // Queue HLS conversion if enabled
            if ($this->isHlsEnabled()) {
                Queue::push(new ProcessHlsConversionJob($path, $path));
                $response['hls_queued'] = true;
            }

            $this->log('Video uploaded successfully', [
                'path' => $path,
                'size' => $file->getSize(),
                'driver' => $this->driverName
            ]);

            return $response;

        } catch (\Exception $e) {
            return $this->createErrorResponse('Upload failed: ' . $e->getMessage());
        }
    }

    public function uploadChunk(string $chunkPath, string $fileName, int $chunkNumber, int $totalChunks, array $options = []): array
    {
        try {
            $chunkDir = $this->config['path'] . '/chunks/' . $fileName;
            $chunkFile = $chunkDir . '/chunk_' . $chunkNumber;

            // Store chunk
            Storage::disk($this->config['disk'])->put($chunkFile, file_get_contents($chunkPath));

            // If this is the last chunk, combine all chunks
            if ($chunkNumber === $totalChunks) {
                return $this->combineChunks($fileName, $totalChunks, $options);
            }

            return [
                'success' => true,
                'chunk_number' => $chunkNumber,
                'total_chunks' => $totalChunks,
                'message' => 'Chunk uploaded successfully'
            ];

        } catch (\Exception $e) {
            return $this->createErrorResponse('Chunk upload failed: ' . $e->getMessage());
        }
    }

    protected function combineChunks(string $fileName, int $totalChunks, array $options = []): array
    {
        try {
            $chunkDir = $this->config['path'] . '/chunks/' . $fileName;
            $finalPath = $this->config['path'] . '/' . $fileName;
            $finalFile = fopen(Storage::disk($this->config['disk'])->path($finalPath), 'wb');

            // Combine all chunks
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkDir . '/chunk_' . $i;
                $chunkContent = Storage::disk($this->config['disk'])->get($chunkFile);
                fwrite($finalFile, $chunkContent);
            }

            fclose($finalFile);

            // Clean up chunks
            Storage::disk($this->config['disk'])->deleteDirectory($chunkDir);

            $metadata = [
                'original_name' => $fileName,
                'size' => Storage::disk($this->config['disk'])->size($finalPath),
                'uploaded_at' => now()->toISOString(),
                'driver' => $this->driverName,
            ];

            $response = $this->createUploadResponse($finalPath, $metadata);

            // Queue HLS conversion if enabled
            if ($this->isHlsEnabled()) {
                Queue::push(new ProcessHlsConversionJob($finalPath, $finalPath));
                $response['hls_queued'] = true;
            }

            $this->log('Chunks combined successfully', [
                'path' => $finalPath,
                'total_chunks' => $totalChunks
            ]);

            return $response;

        } catch (\Exception $e) {
            return $this->createErrorResponse('Failed to combine chunks: ' . $e->getMessage());
        }
    }

    public function delete(string $path): bool
    {
        try {
            $deleted = Storage::disk($this->config['disk'])->delete($path);
            
            if ($deleted) {
                $this->log('Video deleted successfully', ['path' => $path]);
            }

            return $deleted;

        } catch (\Exception $e) {
            $this->log('Failed to delete video: ' . $e->getMessage(), ['path' => $path], 'error');
            return false;
        }
    }

    public function getStreamingUrl(string $path, array $options = []): string
    {
        return Storage::disk($this->config['disk'])->url($path);
    }

    public function getDownloadUrl(string $path, array $options = []): string
    {
        return Storage::disk($this->config['disk'])->url($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->config['disk'])->exists($path);
    }

    public function getMetadata(string $path): array
    {
        try {
            $disk = Storage::disk($this->config['disk']);
            
            return [
                'size' => $disk->size($path),
                'last_modified' => $disk->lastModified($path),
                'mime_type' => $disk->mimeType($path),
                'visibility' => $disk->getVisibility($path),
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function generateThumbnail(string $path, string $time = '00:00:05', array $options = []): string
    {
        try {
            $videoPath = Storage::disk($this->config['disk'])->path($path);
            $thumbnailPath = str_replace(['.mp4', '.avi', '.mov', '.mkv', '.webm'], '.jpg', $path);
            $thumbnailFullPath = Storage::disk($this->config['disk'])->path($thumbnailPath);

            // Create thumbnail directory if it doesn't exist
            $thumbnailDir = dirname($thumbnailFullPath);
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Generate thumbnail using FFmpeg
            $cmd = "ffmpeg -i '{$videoPath}' -ss {$time} -vframes 1 -q:v 2 '{$thumbnailFullPath}' 2>&1";
            exec($cmd, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception("FFmpeg failed: " . implode("\n", $output));
            }

            $this->log('Thumbnail generated successfully', [
                'video_path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'time' => $time
            ]);

            return $thumbnailPath;

        } catch (\Exception $e) {
            $this->log('Failed to generate thumbnail: ' . $e->getMessage(), ['path' => $path], 'error');
            return '';
        }
    }

    public function convertToHls(string $path, array $options = []): array
    {
        try {
            $videoPath = Storage::disk($this->config['disk'])->path($path);
            $hlsDir = str_replace(['.mp4', '.avi', '.mov', '.mkv', '.webm'], '', $path) . '_hls';
            $hlsPath = $hlsDir . '/index.m3u8';
            $hlsFullPath = Storage::disk($this->config['disk'])->path($hlsPath);

            // Create HLS directory
            $hlsDirFull = dirname($hlsFullPath);
            if (!is_dir($hlsDirFull)) {
                mkdir($hlsDirFull, 0755, true);
            }

            // Convert to HLS using FFmpeg
            $segmentDuration = config('video.processing.hls_segment_duration', 10);
            $cmd = "ffmpeg -i '{$videoPath}' -codec:copy -start_number 0 -hls_time {$segmentDuration} -hls_list_size 0 -f hls '{$hlsFullPath}' 2>&1";
            
            exec($cmd, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception("FFmpeg HLS conversion failed: " . implode("\n", $output));
            }

            $this->log('HLS conversion completed', [
                'video_path' => $path,
                'hls_path' => $hlsPath
            ]);

            return [
                'success' => true,
                'hls_path' => $hlsPath,
                'hls_url' => $this->getStreamingUrl($hlsPath),
                'segment_duration' => $segmentDuration
            ];

        } catch (\Exception $e) {
            $this->log('HLS conversion failed: ' . $e->getMessage(), ['path' => $path], 'error');
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getUploadProgress(string $uploadId): array
    {
        // For server driver, we don't have upload progress tracking
        // This would be implemented for cloud drivers
        return [
            'upload_id' => $uploadId,
            'status' => 'completed',
            'progress' => 100,
            'message' => 'Upload completed'
        ];
    }
}
