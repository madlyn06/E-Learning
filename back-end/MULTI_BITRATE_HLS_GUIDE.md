# Multi-Bitrate HLS Implementation Guide

## 🎯 Overview

This implementation provides automatic multi-bitrate HLS (HTTP Live Streaming) conversion for uploaded videos, creating three quality levels: 1080p, 720p, and 480p. This ensures optimal streaming performance across different devices and network conditions.

## 🚀 Features

- **Automatic Quality Conversion**: Every uploaded video is automatically converted to three resolutions
- **Adaptive Bitrate Streaming**: Players can switch between qualities based on network conditions
- **Optimized Encoding**: Uses H.264 video codec and AAC audio codec for maximum compatibility
- **Background Processing**: HLS conversion runs in background jobs to avoid blocking user requests
- **Fallback Support**: Includes single-bitrate fallback for compatibility

## 📁 File Structure

```
elearning-system-server/
├── config/video.php                           # Video processing configuration
├── modules/elearning/
│   ├── src/Jobs/ProcessHlsConversionJob.php  # Multi-bitrate conversion job
│   ├── src/Models/Lesson.php                 # Updated lesson model
│   └── database/migrations/                   # Database schema updates
├── app/Services/Video/ServerVideoDriver.php   # Updated video driver
└── resources/js/components/VideoPlayer.vue    # Frontend video player
```

## ⚙️ Configuration

### Environment Variables

Add these to your `.env` file:

```bash
# Video Processing Configuration
VIDEO_DRIVER=server
VIDEO_HLS_ENABLED=true
VIDEO_HLS_MULTI_BITRATE=true
VIDEO_HLS_SEGMENT_DURATION=10
VIDEO_HLS_SEGMENT_LIST_SIZE=0
VIDEO_THUMBNAIL_ENABLED=true
VIDEO_THUMBNAIL_TIME=00:00:05
VIDEO_COMPRESSION_ENABLED=false
VIDEO_COMPRESSION_QUALITY=medium
VIDEO_QUEUE_CONNECTION=default
VIDEO_QUEUE_NAME=video-processing
VIDEO_RETRY_ATTEMPTS=3
VIDEO_RETRY_DELAY=60
```

### Video Configuration (`config/video.php`)

```php
'processing' => [
    'hls_enabled' => env('VIDEO_HLS_ENABLED', true),
    'hls_multi_bitrate' => env('VIDEO_HLS_MULTI_BITRATE', true),
    'hls_resolutions' => [
        '1080p' => [
            'width' => 1920,
            'height' => 1080,
            'bitrate' => '5000k',
            'audio_bitrate' => '128k',
            'crf' => 18
        ],
        '720p' => [
            'width' => 1280,
            'height' => 720,
            'bitrate' => '2800k',
            'audio_bitrate' => '128k',
            'crf' => 20
        ],
        '480p' => [
            'width' => 854,
            'height' => 480,
            'bitrate' => '1400k',
            'audio_bitrate' => '96k',
            'crf' => 22
        ]
    ],
    // ... other settings
],
```

## 🗄️ Database Schema

### New Fields Added to `elearning__lessons` Table

```sql
ALTER TABLE elearning__lessons ADD COLUMN hls_master_playlist VARCHAR(255) NULL AFTER video_path;
ALTER TABLE elearning__lessons ADD COLUMN hls_1080p_path VARCHAR(255) NULL AFTER hls_master_playlist;
ALTER TABLE elearning__lessons ADD COLUMN hls_720p_path VARCHAR(255) NULL AFTER hls_1080p_path;
ALTER TABLE elearning__lessons ADD COLUMN hls_480p_path VARCHAR(255) NULL AFTER hls_720p_path;

-- Add index for performance
CREATE INDEX lessons_hls_paths_index ON elearning__lessons (hls_master_playlist, hls_1080p_path, hls_720p_path, hls_480p_path);
```

### Migration File

Run the migration to add the new fields:

```bash
php artisan migrate --path=modules/elearning/database/migrations
```

## 🔧 Implementation Details

### 1. Multi-Bitrate FFmpeg Command

The system generates FFmpeg commands like this:

```bash
ffmpeg -i 'input.mp4' \
  -filter:v:v_1080p scale=w=1920:h=1080:force_original_aspect_ratio=decrease \
  -filter:v:v_720p scale=w=1280:h=720:force_original_aspect_ratio=decrease \
  -filter:v:v_480p scale=w=854:h=480:force_original_aspect_ratio=decrease \
  -c:v:v_1080p libx264 -b:v:v_1080p 5000k -maxrate:v_1080p 5000k -bufsize:v_1080p 10000k \
  -c:v:v_720p libx264 -b:v:v_720p 2800k -maxrate:v_720p 2800k -bufsize:v_720p 5600k \
  -c:v:v_480p libx264 -b:v:v_480p 1400k -maxrate:v_480p 1400k -bufsize:v_480p 2800k \
  -c:a aac -b:a 128k \
  -f hls -hls_time 10 -hls_list_size 0 \
  -hls_segment_filename 'output/segment_%03d.ts' \
  -var_stream_map 'v:0,a:0 v:1,a:0 v:2,a:0' \
  -master_pl_name 'output/index.m3u8' \
  'output/1080p.m3u8' 'output/720p.m3u8' 'output/480p.m3u8'
```

### 2. Output File Structure

After conversion, the following files are created:

```
storage/app/hls/{lesson_id}/
├── index.m3u8          # Master playlist (references all qualities)
├── 1080p.m3u8          # 1080p quality playlist
├── 720p.m3u8           # 720p quality playlist
├── 480p.m3u8           # 480p quality playlist
├── segment_000.ts      # Video segments (shared across qualities)
├── segment_001.ts
├── segment_002.ts
└── ...
```

### 3. Master Playlist (index.m3u8)

```m3u8
#EXTM3U
#EXT-X-VERSION:3

#EXT-X-STREAM-INF:BANDWIDTH=5128000,RESOLUTION=1920x1080,CODECS="avc1.640028,mp4a.40.2"
1080p.m3u8

#EXT-X-STREAM-INF:BANDWIDTH=2928000,RESOLUTION=1280x720,CODECS="avc1.640028,mp4a.40.2"
720p.m3u8

#EXT-X-STREAM-INF:BANDWIDTH=1496000,RESOLUTION=854x480,CODECS="avc1.640028,mp4a.40.2"
480p.m3u8
```

## 📱 Frontend Integration

### Vue.js Video Player Component

The `VideoPlayer.vue` component provides:

- **Quality Selection**: Users can manually choose video quality
- **Automatic Switching**: HLS.js can automatically switch based on network conditions
- **Error Handling**: Graceful fallback and retry mechanisms
- **Responsive Design**: Mobile-friendly interface

### Usage Example

```vue
<template>
  <VideoPlayer 
    :lesson="lesson" 
    :autoplay="false" 
    :muted="false" 
  />
</template>

<script>
import VideoPlayer from '@/components/VideoPlayer.vue';

export default {
  components: {
    VideoPlayer
  },
  data() {
    return {
      lesson: {
        id: 1,
        isHlsConverted: true,
        hlsUrls: {
          master: '/storage/app/hls/1/index.m3u8',
          '1080p': '/storage/app/hls/1/1080p.m3u8',
          '720p': '/storage/app/hls/1/720p.m3u8',
          '480p': '/storage/app/hls/1/480p.m3u8'
        }
      }
    };
  }
};
</script>
```

## 🔄 Background Processing

### Queue Configuration

Ensure your queue is properly configured:

```bash
# Start queue worker
php artisan queue:work --queue=video-processing

# Or use supervisor for production
```

### Job Processing

The `ProcessHlsConversionJob` handles:

1. **Video Validation**: Checks file integrity and format
2. **Multi-Bitrate Conversion**: Generates three quality levels
3. **Progress Tracking**: Updates conversion progress in cache
4. **Error Handling**: Retries failed conversions
5. **Database Updates**: Stores HLS file paths

## 📊 Monitoring and Analytics

### Progress Tracking

Conversion progress is stored in cache:

```php
Cache::put('lesson_hls_progress_' . $lessonId, [
    'progress' => 75,
    'message' => 'Converting to HLS format...',
    'updated_at' => now()->toISOString()
], 3600);
```

### Logging

Comprehensive logging for debugging:

```php
Log::info("Multi-bitrate FFmpeg command completed successfully for lesson {$this->lessonId}");
Log::info("Quality playlist created successfully: {$playlistPath} (size: {$playlistSize} bytes)");
```

## 🚨 Troubleshooting

### Common Issues

1. **FFmpeg Not Found**
   ```bash
   # Install FFmpeg
   sudo apt-get install ffmpeg  # Ubuntu/Debian
   brew install ffmpeg          # macOS
   ```

2. **Permission Issues**
   ```bash
   # Ensure storage directories are writable
   chmod -R 775 storage/app/hls
   chown -R www-data:www-data storage/app/hls
   ```

3. **Memory Issues**
   ```bash
   # Increase PHP memory limit
   memory_limit = 512M
   
   # Increase queue timeout
   php artisan queue:work --timeout=3600
   ```

### Debug Commands

```bash
# Check FFmpeg installation
ffmpeg -version

# Test HLS conversion manually
ffmpeg -i test.mp4 -c:v libx264 -c:a aac -hls_time 10 -f hls output.m3u8

# Check queue status
php artisan queue:monitor

# View failed jobs
php artisan queue:failed
```

## 🔒 Security Considerations

1. **File Validation**: Only allow specific video formats
2. **Path Sanitization**: Prevent directory traversal attacks
3. **Access Control**: Ensure users can only access their own videos
4. **Rate Limiting**: Prevent abuse of video upload endpoints

## 📈 Performance Optimization

1. **Segment Duration**: Adjust `hls_segment_duration` based on your needs
2. **Bitrate Settings**: Optimize bitrates for your target audience
3. **CDN Integration**: Use CDN for global video delivery
4. **Caching**: Implement proper caching for HLS playlists

## 🌐 Browser Compatibility

- **Modern Browsers**: HLS.js provides HLS support
- **Safari**: Native HLS support
- **Mobile**: Excellent support on iOS and Android
- **Fallback**: Graceful degradation for unsupported browsers

## 🔮 Future Enhancements

1. **Dynamic Bitrate**: Adaptive bitrate based on content complexity
2. **DRM Support**: Add DRM protection for premium content
3. **Analytics**: Track video playback metrics
4. **Live Streaming**: Extend to live video streaming
5. **AI Optimization**: Use AI to optimize encoding parameters

## 📚 Additional Resources

- [HLS.js Documentation](https://github.com/video-dev/hls.js/)
- [FFmpeg HLS Guide](https://trac.ffmpeg.org/wiki/Encode/H.264)
- [Apple HLS Specification](https://developer.apple.com/documentation/http_live_streaming)
- [Video.js HLS Plugin](https://github.com/videojs/videojs-contrib-hls)

---

This implementation provides a robust, scalable solution for multi-bitrate video streaming that automatically adapts to different network conditions and device capabilities.
