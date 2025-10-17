<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class FileDocumentResource extends JsonResource
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
      'company_name' => $this->company_name,
      'file_version' => $this->file_version,
      'file_size' => $this->file_size,
      'donwload_count' => $this->donwload_count,
      'donwload_url' => $this->donwload_url,
      'post_url' => $this->post_url,
      'required' => $this->required,
      'published_date' => $this->published_date ? $this->published_date->format('Y-m-d') : '',
      'description' => $this->description,
      'content' => $this->content,
      'slug' => Common::buildSlug($this->url),
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : '',
    ];
  }
}
