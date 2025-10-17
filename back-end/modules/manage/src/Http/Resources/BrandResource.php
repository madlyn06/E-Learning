<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class BrandResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'link' => $this->link,
      'description' => $this->description,
      'slug' => Common::buildSlug($this->url),
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
    ];
  }
}
