<?php

namespace Modules\Elearning\Services\Video;

use Modules\Elearning\Contracts\VideoDriverInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class BaseVideoDriver implements VideoDriverInterface
{
    protected array $config;
    protected string $driverName;

    public function __construct(array $config, string $driverName)
    {
        $this->config = $config;
        $this->driverName = $driverName;
    }

    /**
     * Validate file before upload
     */
    public function validateFile($file): bool
    {
        if (!$file instanceof UploadedFile) {
            return false;
        }

        // Check file size
        $maxSize = $this->parseSize($this->config['max_size']);
        if ($file->getSize() > $maxSize) {
            Log::warning("File size exceeds limit for driver {$this->driverName}", [
                'file_size' => $file->getSize(),
                'max_size' => $maxSize,
                'driver' => $this->driverName
            ]);
            return false;
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->config['allowed_types'])) {
            Log::warning("File type not allowed for driver {$this->driverName}", [
                'extension' => $extension,
                'allowed_types' => $this->config['allowed_types'],
                'driver' => $this->driverName
            ]);
            return false;
        }

        return true;
    }

    /**
     * Get driver configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Parse size string to bytes
     */
    protected function parseSize(string $size): int
    {
        $units = [
            'B' => 1,
            'KB' => 1024,
            'MB' => 1024 * 1024,
            'GB' => 1024 * 1024 * 1024,
            'TB' => 1024 * 1024 * 1024 * 1024,
        ];

        $size = strtoupper(trim($size));
        
        foreach ($units as $unit => $multiplier) {
            if (Str::endsWith($size, $unit)) {
                $value = (float) str_replace($unit, '', $size);
                return (int) ($value * $multiplier);
            }
        }

        return (int) $size;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file, array $options = []): string
    {
        $prefix = $options['prefix'] ?? 'video';
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        $extension = $file->getClientOriginalExtension();
        
        return "{$prefix}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Log video operation
     */
    protected function log(string $message, array $context = [], string $level = 'info'): void
    {
        $context['driver'] = $this->driverName;
        
        Log::log($level, "[Video Driver] {$message}", $context);
    }

    /**
     * Get chunk size in bytes
     */
    protected function getChunkSize(): int
    {
        return $this->parseSize($this->config['chunk_size']);
    }

    /**
     * Get concurrent uploads limit
     */
    protected function getConcurrentUploads(): int
    {
        return $this->config['concurrent_uploads'] ?? 1;
    }

    /**
     * Check if HLS is enabled
     */
    protected function isHlsEnabled(): bool
    {
        return config('video.processing.hls_enabled', false) && 
               ($this->config['hls_enabled'] ?? false);
    }

    /**
     * Check if streaming is enabled
     */
    protected function isStreamingEnabled(): bool
    {
        return $this->config['streaming_enabled'] ?? false;
    }

    /**
     * Get video processing queue name
     */
    protected function getQueueName(): string
    {
        return config('video.queue.queue', 'video-processing');
    }

    /**
     * Get retry attempts
     */
    protected function getRetryAttempts(): int
    {
        return config('video.queue.retry_attempts', 3);
    }

    /**
     * Get retry delay
     */
    protected function getRetryDelay(): int
    {
        return config('video.queue.retry_delay', 60);
    }

    /**
     * Validate upload options
     */
    protected function validateUploadOptions(array $options): array
    {
        $defaults = [
            'visibility' => 'public',
            'metadata' => [],
            'tags' => [],
            'acl' => null,
        ];

        return array_merge($defaults, $options);
    }

    /**
     * Get file metadata
     */
    protected function getFileMetadata(UploadedFile $file): array
    {
        return [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'uploaded_at' => now()->toISOString(),
            'driver' => $this->driverName,
        ];
    }

    /**
     * Create upload response
     */
    protected function createUploadResponse(string $path, array $metadata = []): array
    {
        return [
            'success' => true,
            'path' => $path,
            'url' => $this->getStreamingUrl($path),
            'download_url' => $this->getDownloadUrl($path),
            'metadata' => $metadata,
            'driver' => $this->driverName,
            'uploaded_at' => now()->toISOString(),
        ];
    }

    /**
     * Create error response
     */
    protected function createErrorResponse(string $message, array $context = []): array
    {
        $this->log($message, $context, 'error');

        return [
            'success' => false,
            'error' => $message,
            'driver' => $this->driverName,
            'context' => $context,
            'timestamp' => now()->toISOString(),
        ];
    }
}
