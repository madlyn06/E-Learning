<?php

namespace Modules\Elearning\Services\Video;

use Modules\Elearning\Contracts\VideoDriverInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class VideoServiceManager
{
    protected array $drivers = [];
    protected string $defaultDriver;
    protected array $config;

    public function __construct()
    {
        $this->config = config('video', []);
        $this->defaultDriver = $this->config['default'] ?? 'server';
        $this->initializeDrivers();
    }

    /**
     * Initialize all configured drivers
     */
    protected function initializeDrivers(): void
    {
        foreach ($this->config['drivers'] as $name => $driverConfig) {
            $this->drivers[$name] = $this->createDriver($name, $driverConfig);
        }
    }

    /**
     * Create a driver instance
     */
    protected function createDriver(string $name, array $config): VideoDriverInterface
    {
        $driverClass = $this->getDriverClass($name);
        
        if (!class_exists($driverClass)) {
            throw new InvalidArgumentException("Driver class {$driverClass} not found");
        }

        return new $driverClass($config, $name);
    }

    /**
     * Get driver class name
     */
    protected function getDriverClass(string $name): string
    {
        $drivers = [
            'server' => ServerVideoDriver::class,
            'bunny' => BunnyVideoDriver::class,
            // 'digitalocean' => DigitalOceanVideoDriver::class, // TODO: Implement
            // 's3' => S3VideoDriver::class, // TODO: Implement
        ];

        return $drivers[$name] ?? throw new InvalidArgumentException("Unknown driver: {$name}");
    }

    /**
     * Get a specific driver
     */
    public function driver(string $name = null): VideoDriverInterface
    {
        $driverName = $name ?? $this->defaultDriver;
        
        if (!isset($this->drivers[$driverName])) {
            throw new InvalidArgumentException("Driver '{$driverName}' not configured");
        }

        return $this->drivers[$driverName];
    }

    /**
     * Get default driver
     */
    public function getDefaultDriver(): VideoDriverInterface
    {
        return $this->driver();
    }

    /**
     * Get all available drivers
     */
    public function getDrivers(): array
    {
        return array_keys($this->drivers);
    }

    /**
     * Get driver configuration
     */
    public function getDriverConfig(string $name): array
    {
        return $this->config['drivers'][$name] ?? [];
    }

    /**
     * Upload video using default driver
     */
    public function upload(UploadedFile $file, array $options = []): array
    {
        return $this->driver()->upload($file, $options);
    }

    /**
     * Upload video using specific driver
     */
    public function uploadToDriver(string $driverName, UploadedFile $file, array $options = []): array
    {
        return $this->driver($driverName)->upload($file, $options);
    }

    /**
     * Upload video chunk using default driver
     */
    public function uploadChunk(string $chunkPath, string $fileName, int $chunkNumber, int $totalChunks, array $options = []): array
    {
        return $this->driver()->uploadChunk($chunkPath, $fileName, $chunkNumber, $totalChunks, $options);
    }

    /**
     * Upload video chunk using specific driver
     */
    public function uploadChunkToDriver(string $driverName, string $chunkPath, string $fileName, int $chunkNumber, int $totalChunks, array $options = []): array
    {
        return $this->driver($driverName)->uploadChunk($chunkPath, $fileName, $chunkNumber, $totalChunks, $options);
    }

    /**
     * Delete video using default driver
     */
    public function delete(string $path): bool
    {
        return $this->driver()->delete($path);
    }

    /**
     * Delete video using specific driver
     */
    public function deleteFromDriver(string $driverName, string $path): bool
    {
        return $this->driver($driverName)->delete($path);
    }

    /**
     * Get streaming URL using default driver
     */
    public function getStreamingUrl(string $path, array $options = []): string
    {
        return $this->driver()->getStreamingUrl($path, $options);
    }

    /**
     * Get streaming URL using specific driver
     */
    public function getStreamingUrlFromDriver(string $driverName, string $path, array $options = []): string
    {
        return $this->driver($driverName)->getStreamingUrl($path, $options);
    }

    /**
     * Get download URL using default driver
     */
    public function getDownloadUrl(string $path, array $options = []): string
    {
        return $this->driver()->getDownloadUrl($path, $options);
    }

    /**
     * Get download URL using specific driver
     */
    public function getDownloadUrlFromDriver(string $driverName, string $path, array $options = []): string
    {
        return $this->driver($driverName)->getDownloadUrl($path, $options);
    }

    /**
     * Check if video exists using default driver
     */
    public function exists(string $path): bool
    {
        return $this->driver()->exists($path);
    }

    /**
     * Check if video exists using specific driver
     */
    public function existsInDriver(string $driverName, string $path): bool
    {
        return $this->driver($driverName)->exists($path);
    }

    /**
     * Get video metadata using default driver
     */
    public function getMetadata(string $path): array
    {
        return $this->driver()->getMetadata($path);
    }

    /**
     * Get video metadata using specific driver
     */
    public function getMetadataFromDriver(string $driverName, string $path): array
    {
        return $this->driver($driverName)->getMetadata($path);
    }

    /**
     * Generate thumbnail using default driver
     */
    public function generateThumbnail(string $path, string $time = '00:00:05', array $options = []): string
    {
        return $this->driver()->generateThumbnail($path, $time, $options);
    }

    /**
     * Generate thumbnail using specific driver
     */
    public function generateThumbnailFromDriver(string $driverName, string $path, string $time = '00:00:05', array $options = []): string
    {
        return $this->driver($driverName)->generateThumbnail($path, $time, $options);
    }

    /**
     * Convert video to HLS using default driver
     */
    public function convertToHls(string $path, array $options = []): array
    {
        return $this->driver()->convertToHls($path, $options);
    }

    /**
     * Convert video to HLS using specific driver
     */
    public function convertToHlsFromDriver(string $driverName, string $path, array $options = []): array
    {
        return $this->driver($driverName)->convertToHls($path, $options);
    }

    /**
     * Get upload progress using default driver
     */
    public function getUploadProgress(string $uploadId): array
    {
        return $this->driver()->getUploadProgress($uploadId);
    }

    /**
     * Get upload progress using specific driver
     */
    public function getUploadProgressFromDriver(string $driverName, string $uploadId): array
    {
        return $this->driver($driverName)->getUploadProgress($uploadId);
    }

    /**
     * Validate file using default driver
     */
    public function validateFile($file): bool
    {
        return $this->driver()->validateFile($file);
    }

    /**
     * Validate file using specific driver
     */
    public function validateFileWithDriver(string $driverName, $file): bool
    {
        return $this->driver($driverName)->validateFile($file);
    }

    /**
     * Get driver statistics
     */
    public function getDriverStatistics(): array
    {
        $stats = [];
        
        foreach ($this->drivers as $name => $driver) {
            try {
                $config = $driver->getConfig();
                $stats[$name] = [
                    'name' => $name,
                    'max_size' => $config['max_size'] ?? 'Unknown',
                    'allowed_types' => $config['allowed_types'] ?? [],
                    'chunk_size' => $config['chunk_size'] ?? 'Unknown',
                    'concurrent_uploads' => $config['concurrent_uploads'] ?? 1,
                    'streaming_enabled' => $config['streaming_enabled'] ?? false,
                    'hls_enabled' => $config['hls_enabled'] ?? false,
                ];
            } catch (\Exception $e) {
                Log::error("Failed to get statistics for driver {$name}: " . $e->getMessage());
                $stats[$name] = ['error' => $e->getMessage()];
            }
        }

        return $stats;
    }

    /**
     * Test driver connectivity
     */
    public function testDriver(string $driverName): array
    {
        try {
            $driver = $this->driver($driverName);
            
            // Test basic operations
            $testPath = 'test/connection_test.txt';
            $testContent = 'Connection test at ' . now()->toISOString();
            
            // Try to create a test file
            $uploadResult = $driver->upload(
                new \Illuminate\Http\UploadedFile(
                    $this->createTempFile($testContent),
                    'connection_test.txt',
                    'text/plain'
                ),
                ['path' => 'test']
            );
            
            if (!$uploadResult['success']) {
                return [
                    'driver' => $driverName,
                    'status' => 'failed',
                    'error' => $uploadResult['error'] ?? 'Upload test failed'
                ];
            }
            
            // Test file existence
            $exists = $driver->exists($uploadResult['path']);
            
            // Test metadata retrieval
            $metadata = $driver->getMetadata($uploadResult['path']);
            
            // Clean up test file
            $driver->delete($uploadResult['path']);
            
            return [
                'driver' => $driverName,
                'status' => 'success',
                'tests' => [
                    'upload' => true,
                    'exists' => $exists,
                    'metadata' => !empty($metadata),
                    'delete' => true
                ],
                'metadata' => $metadata
            ];
            
        } catch (\Exception $e) {
            return [
                'driver' => $driverName,
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create temporary file for testing
     */
    protected function createTempFile(string $content): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'video_test_');
        file_put_contents($tempFile, $content);
        return $tempFile;
    }

    /**
     * Get recommended driver for file
     */
    public function getRecommendedDriver(UploadedFile $file): string
    {
        $fileSize = $file->getSize();
        $recommendations = [];
        
        foreach ($this->drivers as $name => $driver) {
            try {
                if ($driver->validateFile($file)) {
                    $config = $driver->getConfig();
                    $maxSize = $this->parseSize($config['max_size'] ?? '0');
                    
                    // Score based on file size compatibility
                    $score = 100;
                    if ($fileSize > $maxSize * 0.8) {
                        $score -= 20; // Penalty for large files
                    }
                    
                    // Bonus for streaming capabilities
                    if ($config['streaming_enabled'] ?? false) {
                        $score += 10;
                    }
                    
                    // Bonus for HLS support
                    if ($config['hls_enabled'] ?? false) {
                        $score += 10;
                    }
                    
                    $recommendations[$name] = $score;
                }
            } catch (\Exception $e) {
                $recommendations[$name] = 0;
            }
        }
        
        if (empty($recommendations)) {
            return $this->defaultDriver;
        }
        
        arsort($recommendations);
        return array_key_first($recommendations);
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
}
