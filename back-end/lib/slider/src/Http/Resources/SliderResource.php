<?php

namespace Newnet\Slider\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'image' => $this->image ? $this->image->url : '',
      'description' => $this->description,
      'content' => $this->content,
      'link' => $this->link,
    ];
  }
}
