<?php

namespace Newnet\Seo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class SitemapResource extends JsonResource
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
      'loc' => Common::buildSlug($this->request_path),
      'lastmod' => $this->urlable && $this->urlable->updated_at ? $this->urlable->updated_at->toIso8601String() : $this->updated_at->toIso8601String(),
      'changefreq' => 'daily',
      'priority' => 0.9,
    ];
  }
}
