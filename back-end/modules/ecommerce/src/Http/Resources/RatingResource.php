<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
      'stars' => $this->stars,
      'name' => $this->name,
      'email' => $this->email,
      'comment' => $this->comment,
      'created_at' => $this->created_at->format('Y-m-d h:m:s'),
    ];
  }
}
