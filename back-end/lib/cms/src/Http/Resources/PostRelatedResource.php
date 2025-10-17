<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Newnet\Core\Utils\Common;

class PostRelatedResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $wordsCount = setting('words_on_description', 25);
    return [
      'id' => $this->id,
      'name' => Str::words($this->name, 11, '...'),
      'description' => $this->description ? Str::words($this->description, $wordsCount, '.') : Str::words($this->content, $wordsCount, '.'),
      'image' => $this->image ? $this->image->url : '',
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'created_at' => $this->created_at->toFormattedDateString(),
      'slug' => Common::buildSlug($this->url),
    ];
  }
}
