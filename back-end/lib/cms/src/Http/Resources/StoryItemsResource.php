<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Cms\Utils\StringUtils;

class StoryItemsResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    if ($this->addition_image) {
      $image = $this->addition_image;
    } else {
      $image = $this->image ? $this->image->url : null;
    }
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'content' => StringUtils::replaceImgElement($this->content),
      'link' => $this->link,
      'auto_play_after' => $this->auto_play_after ? $this->auto_play_after : 7,
      'image' => $image,
      'audio' => $this->audio ? $this->audio->url : null,
      'type' => $this->image && $this->image->mime_type == 'video/mp4' ? 'video' : 'image',
      'text_link' => setting('story_text_link', 'Xem Thêm'),
    ];
  }
}
