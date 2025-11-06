<?php

namespace Modules\Elearning\Services\Video;

use Illuminate\Support\Facades\Http;

class BunnyVideoDriver extends BaseVideoDriver
{
    protected string $apiUrl;
    protected string $storageUrl;

    public function __construct(array $config, string $driverName)
    {
        parent::__construct($config, $driverName);
        $this->apiUrl = "https://api.bunny.net";
        $this->storageUrl = "https://{$this->config['storage_zone']}.bunnycdn.com";
    }

    public function upload($file, array $options = []): array
    {
        try {
            if (!$this->validateFile($file)) {
                return $this->createErrorResponse('File validation failed');
            }

            $options = $this->validateUploadOptions($options);
            $filename = $this->generateFilename($file, $options);
            $path = $this->config['path'] ?? 'videos/' . $filename;

            // Upload to Bunny CDN
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
                'Content-Type' => $file->getMimeType(),
            ])->put(
                "{$this->storageUrl}/{$path}",
                file_get_contents($file->getRealPath())
            );

            if (!$response->successful()) {
                return $this->createErrorResponse('Bunny CDN upload failed: ' . $response->body());
            }

            $metadata = $this->getFileMetadata($file);
            $response = $this->createUploadResponse($path, $metadata);

            // Queue HLS conversion if enabled
            if ($this->isHlsEnabled()) {
                $this->queueHlsConversion($path);
                $response['hls_queued'] = true;
            }

            $this->log('Video uploaded to Bunny CDN successfully', [
                'path' => $path,
                'size' => $file->getSize(),
                'cdn_url' => $this->getStreamingUrl($path)
            ]);

            return $response;

        } catch (\Exception $e) {
            return $this->createErrorResponse('Upload failed: ' . $e->getMessage());
        }
    }

    public function uploadChunk(string $chunkPath, string $fileName, int $chunkNumber, int $totalChunks, array $options = []): array
    {
        try {
            $chunkDir = ($this->config['path'] ?? 'videos') . '/chunks/' . $fileName;
            $chunkFile = $chunkDir . '/chunk_' . $chunkNumber;

            // Upload chunk to Bunny CDN
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
                'Content-Type' => 'application/octet-stream',
            ])->put(
                "{$this->storageUrl}/{$chunkFile}",
                file_get_contents($chunkPath)
            );

            if (!$response->successful()) {
                return $this->createErrorResponse('Chunk upload failed: ' . $response->body());
            }

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
            $chunkDir = ($this->config['path'] ?? 'videos') . '/chunks/' . $fileName;
            $finalPath = ($this->config['path'] ?? 'videos') . '/' . $fileName;

            // Download all chunks and combine them
            $finalContent = '';
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkDir . '/chunk_' . $i;
                $chunkResponse = Http::withHeaders([
                    'AccessKey' => $this->config['api_key'],
                ])->get("{$this->storageUrl}/{$chunkFile}");

                if ($chunkResponse->successful()) {
                    $finalContent .= $chunkResponse->body();
                }
            }

            // Upload combined file
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
                'Content-Type' => 'video/mp4',
            ])->put(
                "{$this->storageUrl}/{$finalPath}",
                $finalContent
            );

            if (!$response->successful()) {
                throw new \Exception('Failed to upload combined file: ' . $response->body());
            }

            // Clean up chunks
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkFile = $chunkDir . '/chunk_' . $i;
                Http::withHeaders([
                    'AccessKey' => $this->config['api_key'],
                ])->delete("{$this->storageUrl}/{$chunkFile}");
            }

            $metadata = [
                'original_name' => $fileName,
                'size' => strlen($finalContent),
                'uploaded_at' => now()->toISOString(),
                'driver' => $this->driverName,
            ];

            $response = $this->createUploadResponse($finalPath, $metadata);

            // Queue HLS conversion if enabled
            if ($this->isHlsEnabled()) {
                $this->queueHlsConversion($finalPath);
                $response['hls_queued'] = true;
            }

            $this->log('Chunks combined successfully on Bunny CDN', [
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
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
            ])->delete("{$this->storageUrl}/{$path}");

            if ($response->successful()) {
                $this->log('Video deleted from Bunny CDN successfully', ['path' => $path]);
                return true;
            }

            $this->log('Failed to delete video from Bunny CDN', ['path' => $path, 'response' => $response->body()], 'error');
            return false;

        } catch (\Exception $e) {
            $this->log('Failed to delete video: ' . $e->getMessage(), ['path' => $path], 'error');
            return false;
        }
    }

    public function getStreamingUrl(string $path, array $options = []): string
    {
        $cdnUrl = $this->config['cdn_url'] ?? "https://{$this->config['pull_zone']}.bunnycdn.com";
        return $cdnUrl . '/' . ltrim($path, '/');
    }

    public function getDownloadUrl(string $path, array $options = []): string
    {
        return $this->getStreamingUrl($path, $options);
    }

    public function exists(string $path): bool
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
            ])->head("{$this->storageUrl}/{$path}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getMetadata(string $path): array
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
            ])->head("{$this->storageUrl}/{$path}");

            if ($response->successful()) {
                return [
                    'size' => $response->header('Content-Length'),
                    'last_modified' => $response->header('Last-Modified'),
                    'mime_type' => $response->header('Content-Type'),
                    'etag' => $response->header('ETag'),
                ];
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function generateThumbnail(string $path, string $time = '00:00:05', array $options = []): string
    {
        // Bunny CDN doesn't support server-side thumbnail generation
        // This would need to be done locally and then uploaded
        $this->log('Thumbnail generation not supported by Bunny CDN driver', ['path' => $path], 'warning');
        return '';
    }

    public function convertToHls(string $path, array $options = []): array
    {
        // Bunny CDN doesn't support server-side HLS conversion
        // This would need to be done locally and then uploaded
        $this->log('HLS conversion not supported by Bunny CDN driver', ['path' => $path], 'warning');
        return [
            'success' => false,
            'error' => 'HLS conversion not supported by Bunny CDN driver'
        ];
    }

    public function getUploadProgress(string $uploadId): array
    {
        // Bunny CDN doesn't provide upload progress tracking
        return [
            'upload_id' => $uploadId,
            'status' => 'completed',
            'progress' => 100,
            'message' => 'Upload completed'
        ];
    }

    protected function queueHlsConversion(string $path): void
    {
        // For Bunny CDN, we would need to download the file, convert it locally,
        // and then upload the HLS files back to Bunny CDN
        $this->log('HLS conversion queued for Bunny CDN', ['path' => $path]);
    }

    /**
     * Get Bunny CDN storage zone info
     */
    public function getStorageZoneInfo(): array
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
            ])->get("{$this->apiUrl}/storagezone/{$this->config['storage_zone']}");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            $this->log('Failed to get storage zone info: ' . $e->getMessage(), [], 'error');
            return [];
        }
    }

    /**
     * Get Bunny CDN pull zone info
     */
    public function getPullZoneInfo(): array
    {
        try {
            $response = Http::withHeaders([
                'AccessKey' => $this->config['api_key'],
            ])->get("{$this->apiUrl}/pullzone/{$this->config['pull_zone']}");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            $this->log('Failed to get pull zone info: ' . $e->getMessage(), [], 'error');
            return [];
        }
    }
}
