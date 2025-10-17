<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Manage\Util\Utils;
use Newnet\Cms\Interface\ContentInterface;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class FileDetailResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $content = app(ContentInterface::class)->action(StringUtils::replaceImgElement($this->content));

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
      'content' => $content,
      'slug' => Common::buildSlug($this->url),
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'meta' => new SeoResource($this->seometa, $this),
      'category' => FileCategoryResource::collection($this->file_categories),
      'related' => $this->related_files->map(function ($file) {
        return new FileDocumentResource($file);
      }),
      'download' => Utils::getDownloadCode($this->download_code),
      'published_date_iso' => $this->published_date->toIso8601String(),
    ];
  }
}
