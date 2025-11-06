<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Newnet\Core\Utils\Common;

class PostResource extends JsonResource
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
    $category = $this->categories()->first();
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description ? Str::words($this->description, $wordsCount, '.') : Str::words($this->content, $wordsCount, '.'),
      'url' => $this->url,
      'image' => $this->image ? $this->image->url : '',
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'author' => [
        'name' => $this->author ? $this->author->name : null,
        'slug' =>  $this->author ? $this->author->slug : null,
        // 'avatar' => $this->author && $this->author &&  $this->author->avatar ? $this->author->avatar->url : null
      ],
      'category' =>[
        'name' => count($this->categories) > 0 ? $category->name : null,
        'slug' => count($this->categories) > 0 ? Common::buildSlug($category->url) : null,
      ],
      'comments_count' => $this->comments->count(),
      'created_at' => $this->created_at->toFormattedDateString(),
      'count_like' => $this->like,
      'viewed' => $this->view_count,
      'slug' => Common::buildSlug($this->url),
    ];
  }
}
