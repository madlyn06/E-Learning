<?php

namespace Modules\Elearning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Modules\Elearning\Repositories\LessonRepository;

class ProcessHlsConversionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 3; // Retry 3 times if failed
    public $backoff = 60; // Wait 1 minute between retries

    protected $lessonId;
    protected $videoPath;
    protected $progressKey;
    protected $lessonRepository;

    /**
     * Create a new job instance.
     */
    public function __construct($lessonId, $videoPath)
    {
        $this->lessonId = $lessonId;
        $this->videoPath = $videoPath;
        $this->progressKey = 'lesson_hls_progress_' . $lessonId;
        $this->lessonRepository = app(LessonRepository::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("Starting HLS conversion job for lesson {$this->lessonId}");

            // Check FFmpeg installation
            $this->checkFfmpegInstallation();

            $this->getVideoInformation();
            
            // Update progress to started
            $this->updateProgress(5, 'Starting conversion...');
            
            // Prepare output directory
            $outputDir = storage_path('app/hls/' . $this->lessonId);
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0775, true);
            }
            
            $input = storage_path('app/private/' . $this->videoPath);
            $output = $outputDir . '/index.m3u8';
            
            Log::info("Video file paths for lesson {$this->lessonId}: input={$input}, output={$output}");
            
            // Check if input file exists
            if (!file_exists($input)) {
                Log::error("Input video file not found: {$input}");
                Log::error("Video path from request: {$this->videoPath}");
                Log::error("Storage path: " . storage_path('app'));
                Log::error("Full input path: {$input}");
                
                // List files in the lesson_videos directory for debugging
                $lessonVideosDir = storage_path('app/lesson_videos');
                if (is_dir($lessonVideosDir)) {
                    $files = scandir($lessonVideosDir);
                    Log::info("Files in lesson_videos directory: " . implode(', ', $files));
                }
                
                throw new \Exception("Input video file not found: {$input}");
            }
            
            // Update progress to processing
            $this->updateProgress(10, 'Processing video...');
            
            // Run ffmpeg command for HLS conversion
            $this->runFfmpegConversion($input, $output);
            
            // Update progress to completed
            $this->updateProgress(100, 'Conversion completed');
            
            // Update lesson with HLS path
            $this->updateLessonHlsPath();
            
            Log::info("HLS conversion job completed successfully for lesson {$this->lessonId}");
            
        } catch (\Exception $e) {
            Log::error("HLS conversion job failed for lesson {$this->lessonId}: " . $e->getMessage());
            
            // Update progress to indicate error
            $this->updateProgress(-1, 'Conversion failed: ' . $e->getMessage());
            
            // Handle the error
            $this->handleConversionError($e->getMessage());
            
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    private function getVideoInformation(): void
    {
        try {
            $path = storage_path('app/private/' . $this->videoPath);
            
            // Check if file exists and is readable
            if (!file_exists($path)) {
                Log::warning("Video file not found for lesson {$this->lessonId}: {$path}");
                return;
            }
            
            if (!is_readable($path)) {
                Log::warning("Video file not readable for lesson {$this->lessonId}: {$path}");
                return;
            }
            
            // Update duration_minutes using a safer approach
            $cmd = "ffmpeg -i '{$path}' 2>&1 | grep 'Duration' | head -1 | awk '{print $2}' | tr -d ,";
            $result = Process::forever()->run($cmd);
            $output = $result->output();
            
            if ($result->successful() && !empty(trim(string: $output))) {
                $duration = trim($output);
                if (preg_match('/^\d{2}:\d{2}:\d{2}/', $duration)) {
                    $this->lessonRepository->updateById([
                        'duration_minutes' => $duration,
                    ], $this->lessonId);
                    Log::info("Updated duration for lesson {$this->lessonId}: {$duration}");
                }
            } else {
                Log::warning("Failed to get duration for lesson {$this->lessonId}. Output: " . $output);
            }
        } catch (\Exception $e) {
            Log::error("Error getting video information for lesson {$this->lessonId}: " . $e->getMessage());
        }
    }

    /**
     * Run FFmpeg conversion command
     */
    private function runFfmpegConversion(string $input, string $output): void
    {
        // Update progress to 25%
        $this->updateProgress(25, 'Converting to HLS format...');
        
        // Verify input file exists and is readable
        if (!file_exists($input)) {
            throw new \Exception("Input video file not found: {$input}");
        }
        
        if (!is_readable($input)) {
            throw new \Exception("Input video file not readable: {$input}");
        }
        
        // Verify it's actually a video file by checking file size and extension
        $fileSize = filesize($input);
        if ($fileSize === 0) {
            throw new \Exception("Input video file is empty: {$input}");
        }
        
        $extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));
        $allowedExtensions = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new \Exception("Invalid video file extension: {$extension}. Allowed: " . implode(', ', $allowedExtensions));
        }
        
        // Try to get video information using FFprobe for additional validation
        $this->validateVideoFileWithFfprobe($input);
        
        Log::info("Video file validation passed for lesson {$this->lessonId}: {$input} (size: {$fileSize} bytes, extension: {$extension})");
        
        // Check if multi-bitrate is enabled
        if (config('video.processing.hls_multi_bitrate', true)) {
            $this->runMultiBitrateConversion($input, $output);
        } else {
            $this->runSingleBitrateConversion($input, $output);
        }
    }

    /**
     * Update lesson with HLS path
     */
    private function updateLessonHlsPath(): void
    {
        $lesson = $this->lessonRepository->find($this->lessonId);
        if ($lesson) {
            $updateData = [
                'metadata' => json_encode([
                    'hls_conversion_completed_at' => now()->toISOString(),
                    'hls_conversion_status' => 'completed'
                ])
            ];
            
            // Check if multi-bitrate is enabled
            if (config('video.processing.hls_multi_bitrate', true)) {
                $updateData = array_merge($updateData, [
                    'hls_master_playlist' => 'index.m3u8',
                    'hls_1080p_path' => '1080p.m3u8',
                    'hls_720p_path' => '720p.m3u8',
                    'hls_480p_path' => '480p.m3u8',
                ]);
            } else {
                $updateData['video_path'] = 'hls/' . $this->lessonId . '/index.m3u8';
            }
            
            $this->lessonRepository->updateById($updateData, $this->lessonId);
            
            Log::info("Updated lesson {$this->lessonId} with HLS paths: " . json_encode($updateData));
        }
    }

    /**
     * Handle conversion errors
     */
    private function handleConversionError(string $errorMessage): void
    {
        $lesson = $this->lessonRepository->find($this->lessonId);
        if ($lesson) {
            $this->lessonRepository->updateById([
                'metadata' => json_encode([
                    'hls_conversion_error' => $errorMessage,
                    'hls_conversion_failed_at' => now()->toISOString(),
                    'hls_conversion_status' => 'failed'
                ])
            ], $this->lessonId);
        }
        
        // TODO We will send notifications to administrators here
        // Notification::route('mail', config('admin.email'))->notify(new HlsConversionFailed($this->lessonId, $errorMessage));
    }

    /**
     * Update progress in cache
     */
    private function updateProgress(int $progress, string $message): void
    {
        Cache::put($this->progressKey, [
            'progress' => $progress,
            'message' => $message,
            'updated_at' => now()->toISOString()
        ], 3600); // 1 hour TTL
    }

    /**
     * Validate video file using FFprobe
     */
    private function validateVideoFileWithFfprobe(string $input): void
    {
        try {
            $cmd = "ffprobe -v quiet -print_format json -show_format -show_streams '{$input}' 2>&1";
            $result = Process::forever()->run($cmd);
            
            if (!$result->successful()) {
                Log::warning("FFprobe validation failed for lesson {$this->lessonId}: " . $result->output());
                return; // Don't fail the job, just log a warning
            }
            
            $output = $result->output();
            $videoInfo = json_decode($output, true);
            
            if ($videoInfo && isset($videoInfo['streams'])) {
                $hasVideo = false;
                $hasAudio = false;
                
                foreach ($videoInfo['streams'] as $stream) {
                    if ($stream['codec_type'] === 'video') {
                        $hasVideo = true;
                    }
                    if ($stream['codec_type'] === 'audio') {
                        $hasAudio = true;
                    }
                }
                
                if (!$hasVideo) {
                    throw new \Exception("File does not contain a video stream");
                }
                
                Log::info("FFprobe validation passed for lesson {$this->lessonId}: video={$hasVideo}, audio={$hasAudio}");
            }
        } catch (\Exception $e) {
            Log::warning("FFprobe validation error for lesson {$this->lessonId}: " . $e->getMessage());
            // Don't fail the job, just log a warning
        }
    }

    /**
     * Run multi-bitrate HLS conversion
     */
    private function runMultiBitrateConversion(string $input, string $output): void
    {
        $this->updateProgress(30, 'Starting multi-bitrate conversion...');
        
        // Ensure output directory exists
        $outputDir = dirname($output);
        if (!file_exists($outputDir)) {
            if (!mkdir($outputDir, 0775, true)) {
                throw new \Exception("Failed to create output directory: {$outputDir}");
            }
        }
        
        $resolutions = config('video.processing.hls_resolutions', []);
        $segmentDuration = config('video.processing.hls_segment_duration', 10);
        $segmentListSize = config('video.processing.hls_segment_list_size', 0);
        
        // Convert each quality separately (more reliable approach)
        foreach ($resolutions as $quality => $config) {
            $this->updateProgress(35 + (array_search($quality, array_keys($resolutions)) * 10), "Converting {$quality} quality...");
            
            $qualityOutput = $outputDir . '/' . $quality . '.m3u8';
            $cmd = $this->buildSingleQualityCommand($input, $qualityOutput, $config, $segmentDuration, $segmentListSize);
            
            Log::info("Executing {$quality} FFmpeg command for lesson {$this->lessonId}: {$cmd}");
            
            // Execute command
            $commandOutput = [];
            $returnCode = 0;
            exec($cmd, $commandOutput, $returnCode);
            
            if ($returnCode !== 0) {
                $errorOutput = implode("\n", $commandOutput);
                Log::error("{$quality} FFmpeg command failed for lesson {$this->lessonId} with code {$returnCode}: {$errorOutput}");
                throw new \Exception("{$quality} FFmpeg failed with code {$returnCode}: {$errorOutput}");
            }
            
            Log::info("{$quality} FFmpeg command completed successfully for lesson {$this->lessonId}");
        }
        
        // Create master playlist
        $this->createMasterPlaylist($output, $resolutions);
        
        $this->updateProgress(75, 'Multi-bitrate conversion completed');
    }
    
    /**
     * Run single bitrate HLS conversion (fallback)
     */
    private function runSingleBitrateConversion(string $input, string $output): void
    {
        $this->updateProgress(30, 'Starting single bitrate conversion...');
        
        // Ensure output directory exists
        $outputDir = dirname($output);
        if (!file_exists($outputDir)) {
            if (!mkdir($outputDir, 0775, true)) {
                throw new \Exception("Failed to create output directory: {$outputDir}");
            }
        }
        
        // Build FFmpeg command - use proper codec settings for HLS
        $cmd = "ffmpeg -i '{$input}' -c:v libx264 -c:a aac -preset fast -crf 23 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls '{$output}' 2>&1";
        
        // Alternative simpler command if the first one fails
        $simpleCmd = "ffmpeg -i '{$input}' -c:v libx264 -c:a aac -hls_time 10 -f hls '{$output}' 2>&1";
        
        Log::info("Executing single bitrate FFmpeg command for lesson {$this->lessonId}: {$cmd}");
        
        // Execute command and capture output
        $commandOutput = [];
        $returnCode = 0;
        exec($cmd, $commandOutput, $returnCode);
        
        if ($returnCode !== 0) {
            $errorOutput = implode("\n", $commandOutput);
            Log::warning("First FFmpeg command failed for lesson {$this->lessonId} with code {$returnCode}. Trying fallback command...");
            
            // Try a simpler fallback command
            $fallbackCmd = $simpleCmd;
            Log::info("Executing fallback FFmpeg command for lesson {$this->lessonId}: {$fallbackCmd}");
            
            $commandOutput = [];
            exec($fallbackCmd, $commandOutput, $returnCode);
            
            if ($returnCode !== 0) {
                $errorOutput = implode("\n", $commandOutput);
                Log::error("Both FFmpeg commands failed for lesson {$this->lessonId}. Last error code: {$returnCode}: {$errorOutput}");
                throw new \Exception("FFmpeg failed with code {$returnCode}: {$errorOutput}");
            }
            
            Log::info("Fallback FFmpeg command succeeded for lesson {$this->lessonId}");
        }
        
        Log::info("Single bitrate FFmpeg command completed successfully for lesson {$this->lessonId}");
        
        // Update progress to 75%
        $this->updateProgress(75, 'Finalizing conversion...');
        
        // Verify output file exists and has content
        if (!file_exists($output)) {
            throw new \Exception("HLS output file was not created: {$output}");
        }
        
        $outputSize = filesize($output);
        if ($outputSize === 0) {
            throw new \Exception("HLS output file is empty: {$output}");
        }
        
        Log::info("HLS output file created successfully for lesson {$this->lessonId}: {$output} (size: {$outputSize} bytes)");
    }
    
    /**
     * Build single quality FFmpeg command
     */
    private function buildSingleQualityCommand(string $input, string $output, array $config, int $segmentDuration, int $segmentListSize): string
    {
        $outputDir = dirname($output);
        
        // Ensure dimensions are even numbers (required by H.264 encoder)
        $dimensions = $this->ensureEvenDimensions($config['width'], $config['height']);
        
        $cmd = "ffmpeg -i '{$input}' ";
        $cmd .= "-c:v libx264 -b:v {$config['bitrate']} -maxrate {$config['bitrate']} -bufsize " . $this->calculateBufferSize($config['bitrate']) . " ";
        $cmd .= "-c:a aac -b:a {$config['audio_bitrate']} ";
        $cmd .= "-vf \"scale=w={$dimensions['width']}:h={$dimensions['height']}:force_original_aspect_ratio=decrease,pad=w={$dimensions['width']}:h={$dimensions['height']}:x=(ow-iw)/2:y=(oh-ih)/2:color=black\" ";
        $cmd .= "-f hls ";
        $cmd .= "-hls_time {$segmentDuration} ";
        $cmd .= "-hls_list_size {$segmentListSize} ";
        $cmd .= "-hls_segment_filename '{$outputDir}/segment_{$dimensions['width']}x{$dimensions['height']}_%03d.ts' ";
        $cmd .= "'{$output}' 2>&1";
        
        return $cmd;
    }
    
    /**
     * Create master playlist
     */
    private function createMasterPlaylist(string $masterOutput, array $resolutions): void
    {
        $outputDir = dirname($masterOutput);
        $masterContent = "#EXTM3U\n";
        $masterContent .= "#EXT-X-VERSION:3\n\n";
        
        foreach ($resolutions as $quality => $config) {
            $masterContent .= "#EXT-X-STREAM-INF:BANDWIDTH=" . ($this->getBandwidth($config['bitrate']) * 1000) . ",RESOLUTION={$config['width']}x{$config['height']}\n";
            $masterContent .= "{$quality}.m3u8\n";
        }
        
        if (file_put_contents($masterOutput, $masterContent) === false) {
            throw new \Exception("Failed to create master playlist: {$masterOutput}");
        }
        
        Log::info("Master playlist created successfully: {$masterOutput}");
    }
    
    /**
     * Get bandwidth from bitrate string
     */
    private function getBandwidth(string $bitrate): int
    {
        $numericValue = (int) preg_replace('/[^0-9]/', '', $bitrate);
        if (strpos($bitrate, 'M') !== false) {
            return $numericValue * 1000; // Convert M to k
        }
        return $numericValue; // Already in k
    }
    
    /**
     * Ensure dimensions are even numbers (required by H.264 encoder)
     */
    private function ensureEvenDimensions(int $width, int $height): array
    {
        return [
            'width' => $width % 2 === 0 ? $width : $width - 1,
            'height' => $height % 2 === 0 ? $height : $height - 1
        ];
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
        } else {
            return $bufferSize . 'k'; // Default to k
        }
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
            
            if (!preg_match('/^\d+[kM]$/', $config['audio_bitrate'])) {
                throw new \Exception("Invalid audio bitrate format for quality {$quality}: {$config['audio_bitrate']}");
            }
        }
    }
    
    /**
     * Verify multi-bitrate output files
     */
    private function verifyMultiBitrateOutput(string $output, array $resolutions): void
    {
        $outputDir = dirname($output);
        
        // Check master playlist
        if (!file_exists($output)) {
            throw new \Exception("Master HLS playlist was not created: {$output}");
        }
        
        // Check individual quality playlists
        foreach (array_keys($resolutions) as $quality) {
            $playlistPath = $outputDir . '/' . $quality . '.m3u8';
            if (!file_exists($playlistPath)) {
                throw new \Exception("Quality playlist was not created: {$playlistPath}");
            }
            
            $playlistSize = filesize($playlistPath);
            if ($playlistSize === 0) {
                throw new \Exception("Quality playlist is empty: {$playlistPath}");
            }
            
            Log::info("Quality playlist created successfully: {$playlistPath} (size: {$playlistSize} bytes)");
        }
        
        // Check segment files
        $segmentFiles = glob($outputDir . '/segment_*.ts');
        if (empty($segmentFiles)) {
            throw new \Exception("No segment files were created");
        }
        
        Log::info("Multi-bitrate HLS conversion completed successfully. Created " . count($segmentFiles) . " segment files.");
    }

    /**
     * Check FFmpeg installation
     */
    private function checkFfmpegInstallation(): void
    {
        $cmd = "ffmpeg -version 2>&1";
        $result = Process::forever()->run($cmd);
        
        if (!$result->successful()) {
            Log::error("FFmpeg is not installed or not accessible");
            throw new \Exception("FFmpeg is not installed or not accessible. Please install FFmpeg first.");
        }
        
        $output = $result->output();
        $version = explode("\n", $output)[0] ?? 'Unknown version';
        Log::info("FFmpeg version: {$version}");
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("HLS conversion job permanently failed for lesson {$this->lessonId}: " . $exception->getMessage());
        
        // Update progress to indicate permanent failure
        Cache::put($this->progressKey, [
            'progress' => -1,
            'message' => 'Conversion permanently failed after retries',
            'updated_at' => now()->toISOString()
        ], 3600);
        
        // Handle the permanent failure
        $this->handleConversionError('Job permanently failed after retries: ' . $exception->getMessage());
    }
}
