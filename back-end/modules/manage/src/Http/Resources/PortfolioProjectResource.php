<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class PortfolioProjectResource extends JsonResource
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
      'category' => $this->portfolioCategory ? $this->portfolioCategory->name : null,
      'category_slug' => $this->portfolioCategory ? Common::buildSlug($this->portfolioCategory->url) : null,
      'description' => $this->description,
      'content' => $this->content,
      'client_name' => $this->client_name,
      'year' => $this->year,
      'author' => $this->author,
      'location' => $this->location,
      'icon' => $this->icon,
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'slug' => Common::buildSlug($this->url),
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
