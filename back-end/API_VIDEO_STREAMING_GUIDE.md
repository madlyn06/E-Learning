# Video Streaming API Guide

## 🎯 Overview

This guide covers the enhanced video streaming API endpoints that provide multi-bitrate HLS support with automatic network adaptation. The system automatically converts uploaded videos to three quality levels (1080p, 720p, 480p) and intelligently selects the best quality based on network conditions.

## 🚀 API Endpoints

### **1. Get Lesson Details (Enhanced)**

**Endpoint:** `GET /api/lessons/{lesson}`

**Description:** Returns lesson information including enhanced video data with multi-bitrate HLS support.

**Response Example:**
```json
{
  "success": true,
  "message": "Lesson retrieved successfully",
  "data": {
    "id": 1,
    "name": "Introduction to Laravel",
    "type": "video",
    "video": {
      "type": "hls",
      "hls_enabled": true,
      "multi_bitrate": true,
      "master_playlist": "https://example.com/storage/app/hls/1/index.m3u8",
      "qualities": {
        "1080p": {
          "url": "https://example.com/storage/app/hls/1/1080p.m3u8",
          "width": 1920,
          "height": 1080,
          "bitrate": "5000k",
          "audio_bitrate": "128k",
          "bandwidth": 5128000,
          "codecs": "avc1.640028,mp4a.40.2"
        },
        "720p": {
          "url": "https://example.com/storage/app/hls/1/720p.m3u8",
          "width": 1280,
          "height": 720,
          "bitrate": "2800k",
          "audio_bitrate": "128k",
          "bandwidth": 2928000,
          "codecs": "avc1.640028,mp4a.40.2"
        },
        "480p": {
          "url": "https://example.com/storage/app/hls/1/480p.m3u8",
          "width": 854,
          "height": 480,
          "bitrate": "1400k",
          "audio_bitrate": "96k",
          "bandwidth": 1496000,
          "codecs": "avc1.640028,mp4a.40.2"
        }
      },
      "fallback": "https://example.com/storage/app/lesson_videos/video.mp4",
      "metadata": {
        "duration": "00:15:30",
        "original_name": "laravel-intro.mp4",
        "size": "52428800",
        "mime_type": "video/mp4",
        "extension": "mp4"
      }
    }
  }
}
```

### **2. Get Video Streaming URLs (New)**

**Endpoint:** `GET /api/lessons/{lesson}/video-streaming`

**Description:** Returns comprehensive video streaming information with network-aware quality recommendations.

**Query Parameters:**
- `network_type` (optional): Force specific network type (`slow`, `medium`, `fast`, `auto`)

**Response Example:**
```json
{
  "success": true,
  "message": "Video streaming URLs retrieved successfully",
  "data": {
    "lesson_id": 1,
    "lesson_name": "Introduction to Laravel",
    "network_detected": "medium",
    "recommended_quality": "720p",
    "available_qualities": ["1080p", "720p", "480p"],
    "streaming_data": {
      "type": "hls",
      "hls_enabled": true,
      "multi_bitrate": true,
      "master_playlist": "https://example.com/storage/app/hls/1/index.m3u8",
      "qualities": {
        "1080p": { /* quality details */ },
        "720p": { /* quality details */ },
        "480p": { /* quality details */ }
      }
    },
    "auto_adaptation": {
      "enabled": true,
      "algorithm": "network_aware",
      "fallback_strategy": "progressive_downgrade",
      "buffer_threshold": 5,
      "quality_switch_threshold": 0.8
    },
    "metadata": {
      "duration": "00:15:30",
      "file_size": "52428800",
      "original_format": "mp4",
      "hls_conversion_status": "completed",
      "last_updated": "2025-08-25T10:30:00Z"
    }
  }
}
```

### **3. Get HLS Progress (Existing)**

**Endpoint:** `GET /api/lessons/{lesson}/hls-progress`

**Description:** Returns the current HLS conversion progress for video lessons.

**Response Example:**
```json
{
  "success": true,
  "message": "HLS progress retrieved successfully",
  "data": {
    "progress": {
      "progress": 75,
      "message": "Converting to HLS format...",
      "updated_at": "2025-08-25T10:30:00Z"
    }
  }
}
```

## 🔧 Network Detection

### **Automatic Detection**

The API automatically detects network conditions based on:

1. **User-Agent Analysis:**
   - Mobile browsers → Medium network
   - Desktop browsers → Fast network
   - Specific mobile browsers (Opera Mini, UC Browser) → Slow network

2. **Network Information API** (if available):
   - `slow-2g`, `2g`, `3g` → Slow network
   - `4g` → Medium network
   - `5g` → Fast network

3. **Manual Override:**
   - Users can specify `network_type` parameter

### **Network Types**

| Type | Description | Recommended Quality | Use Case |
|------|-------------|-------------------|----------|
| `slow` | 2G/3G networks | 480p | Mobile data, slow connections |
| `medium` | 4G networks | 720p | Standard mobile, moderate connections |
| `fast` | WiFi/5G networks | 1080p | Home/office, fast connections |
| `auto` | Automatic detection | Smart selection | Default behavior |

## 📱 Frontend Integration

### **Using the Network-Aware Video Player**

```vue
<template>
  <NetworkAwareVideoPlayer 
    :lesson="lesson" 
    :autoplay="false" 
    :showMetrics="true"
    :autoAdaptationEnabled="true"
  />
</template>

<script>
import NetworkAwareVideoPlayer from '@/components/NetworkAwareVideoPlayer.vue';

export default {
  components: {
    NetworkAwareVideoPlayer
  },
  data() {
    return {
      lesson: null
    };
  },
  async mounted() {
    await this.loadLesson();
  },
  methods: {
    async loadLesson() {
      try {
        // Get lesson with video streaming data
        const response = await this.$http.get(`/api/lessons/${this.$route.params.id}/video-streaming`);
        this.lesson = response.data.data;
      } catch (error) {
        console.error('Failed to load lesson:', error);
      }
    }
  }
};
</script>
```

### **Manual Quality Selection**

```javascript
// Get recommended quality for specific network
const response = await fetch(`/api/lessons/1/video-streaming?network_type=slow`);
const data = await response.json();
const recommendedQuality = data.data.recommended_quality; // "480p"

// Get all available qualities
const availableQualities = data.data.available_qualities; // ["1080p", "720p", "480p"]
```

## 🌐 HLS Playlist Structure

### **Master Playlist (index.m3u8)**

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

### **Quality-Specific Playlists**

Each quality has its own playlist (e.g., `720p.m3u8`):

```m3u8
#EXTM3U
#EXT-X-VERSION:3
#EXT-X-TARGETDURATION:10
#EXT-X-MEDIA-SEQUENCE:0

#EXTINF:10.0,
segment_000.ts

#EXTINF:10.0,
segment_001.ts

#EXT-X-ENDLIST
```

## 🔄 Auto-Adaptation Features

### **1. Buffer-Based Switching**

- **Buffer Threshold:** 5 seconds minimum buffer
- **Quality Switch Threshold:** 80% buffer capacity
- **Progressive Downgrade:** Automatically switches to lower quality if buffer depletes

### **2. Network-Aware Selection**

- **Fast Networks:** Starts with 1080p, can switch up/down
- **Medium Networks:** Starts with 720p, balanced approach
- **Slow Networks:** Starts with 480p, minimal switching

### **3. Performance Monitoring**

- **Buffer Health:** Real-time buffer level monitoring
- **Network Stability:** Connection quality assessment
- **Quality Switches:** Tracks adaptation frequency

## 📊 Response Status Codes

| Status | Description |
|--------|-------------|
| `200` | Success - Video streaming data retrieved |
| `400` | Bad Request - Invalid lesson type or parameters |
| `401` | Unauthorized - User not authenticated |
| `404` | Not Found - Lesson doesn't exist |
| `500` | Server Error - Internal processing error |

## 🚨 Error Handling

### **Common Error Responses**

```json
{
  "success": false,
  "message": "Video streaming is only available for video lessons",
  "errors": null,
  "timestamp": "2025-08-25T10:30:00Z"
}
```

### **Error Types**

1. **Lesson Type Mismatch:** Only video lessons support streaming
2. **HLS Not Ready:** Video conversion still in progress
3. **Network Issues:** Connection problems during streaming
4. **File Not Found:** Video files missing or corrupted

## 🔒 Security Considerations

### **Access Control**

- Users can only access lessons they're enrolled in
- Authentication required for all streaming endpoints
- Rate limiting on video streaming requests

### **File Security**

- Video files stored outside web root
- Signed URLs for temporary access (if implemented)
- Validation of lesson ownership before streaming

## 📈 Performance Optimization

### **Caching Strategies**

1. **HLS Playlists:** Cache master and quality playlists
2. **Video Segments:** CDN distribution for global access
3. **API Responses:** Cache lesson data and streaming info

### **CDN Integration**

```javascript
// Example CDN configuration
const cdnBaseUrl = 'https://cdn.example.com';
const videoUrl = `${cdnBaseUrl}/hls/${lessonId}/${quality}.m3u8`;
```

## 🧪 Testing

### **Test Network Types**

```bash
# Test slow network
curl "https://api.example.com/api/lessons/1/video-streaming?network_type=slow"

# Test medium network
curl "https://api.example.com/api/lessons/1/video-streaming?network_type=medium"

# Test fast network
curl "https://api.example.com/api/lessons/1/video-streaming?network_type=fast"

# Test auto detection
curl "https://api.example.com/api/lessons/1/video-streaming"
```

### **Test Video Player**

```javascript
// Test different network conditions
const testNetworks = ['slow', 'medium', 'fast'];

testNetworks.forEach(async (networkType) => {
  const response = await fetch(`/api/lessons/1/video-streaming?network_type=${networkType}`);
  const data = await response.json();
  console.log(`${networkType}: ${data.data.recommended_quality}`);
});
```

## 🔮 Future Enhancements

### **Planned Features**

1. **Dynamic Bitrate:** Adaptive bitrate based on content complexity
2. **DRM Support:** Content protection for premium videos
3. **Analytics:** Detailed playback metrics and user behavior
4. **Live Streaming:** Extend to live video broadcasts
5. **AI Optimization:** Machine learning for quality selection

### **Advanced Network Detection**

1. **Speed Testing:** Real-time bandwidth measurement
2. **Latency Monitoring:** Connection quality assessment
3. **Geographic Optimization:** Location-based quality selection
4. **Device Capabilities:** Hardware-based quality limits

---

This API provides a robust, scalable solution for multi-bitrate video streaming that automatically adapts to different network conditions and device capabilities, ensuring optimal user experience across all platforms.
