<?php

namespace Modules\Elearning\Services\Video;

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

            // Convert to HLS using FFmpeg - check if multi-bitrate is enabled
            $segmentDuration = config('video.processing.hls_segment_duration', 10);
            
            if (config('video.processing.hls_multi_bitrate', true)) {
                $cmd = $this->buildMultiBitrateHlsCommand($videoPath, $hlsFullPath, $segmentDuration);
            } else {
                $cmd = "ffmpeg -i '{$videoPath}' -c:v libx264 -c:a aac -preset fast -crf 23 -start_number 0 -hls_time {$segmentDuration} -hls_list_size 0 -f hls '{$hlsFullPath}' 2>&1";
            }
            
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

    /**
     * Build multi-bitrate HLS FFmpeg command
     */
    private function buildMultiBitrateHlsCommand(string $videoPath, string $hlsFullPath, int $segmentDuration): string
    {
        $outputDir = dirname($hlsFullPath);
        $resolutions = config('video.processing.hls_resolutions', []);
        
        // Validate resolutions configuration
        $this->validateResolutionsConfig($resolutions);
        
        $cmd = "ffmpeg -i '{$videoPath}' ";
        
        // Add video filters for each resolution with proper stream mapping
        $cmd .= "-filter:v:v_1080p scale=w={$resolutions['1080p']['width']}:h={$resolutions['1080p']['height']}:force_original_aspect_ratio=decrease ";
        $cmd .= "-filter:v:v_720p scale=w={$resolutions['720p']['width']}:h={$resolutions['720p']['height']}:force_original_aspect_ratio=decrease ";
        $cmd .= "-filter:v:v_480p scale=w={$resolutions['480p']['width']}:h={$resolutions['480p']['height']}:force_original_aspect_ratio=decrease ";
        
        // Add codec settings for each resolution
        $cmd .= "-c:v:v_1080p libx264 -b:v:v_1080p {$resolutions['1080p']['bitrate']} -maxrate:v_1080p {$resolutions['1080p']['bitrate']} -bufsize:v_1080p " . $this->calculateBufferSize($resolutions['1080p']['bitrate']) . " ";
        $cmd .= "-c:v:v_720p libx264 -b:v:v_720p {$resolutions['720p']['bitrate']} -maxrate:v_720p {$resolutions['720p']['bitrate']} -bufsize:v_720p " . $this->calculateBufferSize($resolutions['720p']['bitrate']) . " ";
        $cmd .= "-c:v:v_480p libx264 -b:v:v_480p {$resolutions['480p']['bitrate']} -maxrate:v_480p {$resolutions['480p']['bitrate']} -bufsize:v_480p " . $this->calculateBufferSize($resolutions['480p']['bitrate']) . " ";
        
        // Add audio settings
        $cmd .= "-c:a aac -b:a {$resolutions['1080p']['audio_bitrate']} ";
        
        // Add HLS settings with proper multi-bitrate syntax
        $cmd .= "-f hls ";
        $cmd .= "-hls_time {$segmentDuration} ";
        $cmd .= "-hls_list_size 0 ";
        $cmd .= "-hls_segment_filename '{$outputDir}/segment_%03d.ts' ";
        $cmd .= "-var_stream_map 'v:0,a:0 v:1,a:0 v:2,a:0' ";
        $cmd .= "-master_pl_name '{$hlsFullPath}' ";
        $cmd .= "'{$outputDir}/1080p.m3u8' '{$outputDir}/720p.m3u8' '{$outputDir}/480p.m3u8' 2>&1";
        
        return $cmd;
    }

    /**
     * Calculate buffer size from bitrate string
     */
    private function calculateBufferSize(string $bitrate): string
    {
        // Extract numeric value from bitrate string (e.g., "5000k" -> 5000)
        $numericValue = (int) preg_replace('/[^0-9]/', '', $bitrate);
        
        // Calculate buffer size (2x the bitrate)
        $bufferSize = $numericValue * 2;
        
        // Return with appropriate unit
        if (strpos($bitrate, 'k') !== false) {
            return $bufferSize . 'k';
        } elseif (strpos($bitrate, 'M') !== false) {
            return $bufferSize . 'M';
        }
        return $bufferSize . 'k'; // Default to k
    }

    /**
     * Validate resolutions configuration
     */
    private function validateResolutionsConfig(array $resolutions): void
    {
        $requiredFields = ['width', 'height', 'bitrate', 'audio_bitrate'];
        
        foreach ($resolutions as $quality => $config) {
            if (!is_array($config)) {
                throw new \Exception("Invalid configuration for quality {$quality}: must be an array");
            }
            
            foreach ($requiredFields as $field) {
                if (!isset($config[$field])) {
                    throw new \Exception("Missing required field '{$field}' for quality {$quality}");
                }
            }
            
            // Validate numeric fields
            if (!is_numeric($config['width']) || !is_numeric($config['height'])) {
                throw new \Exception("Width and height must be numeric for quality {$quality}");
            }
            
            // Validate bitrate format
            if (!preg_match('/^\d+[kM]$/', $config['bitrate'])) {
                throw new \Exception("Invalid bitrate format for quality {$quality}: {$config['bitrate']}");
            }
            
            if (!preg_match('/^\d+k$/', $config['audio_bitrate'])) {
                throw new \Exception("Invalid audio bitrate format for quality {$quality}: {$config['audio_bitrate']}");
            }
        }
    }
}
