<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Cms\Http\Resources\FaqResource;
use Newnet\Cms\Interface\ContentInterface;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Http\Resources\SeoResource;

class PostDetailResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    $content = app(ContentInterface::class)->action(StringUtils::replaceImgElement($this->content));

    $author = [
      'slug' => $this->author->slug ?? null,
      'name' => $this->author && $this->author->display_name ? $this->author->display_name : $this->author->name ?? null,
      'description' => $this->author->description ?? null,
      'avatar' => $this->author->image ?? null,
    ];

    if (setting('is_display_facebook')) {
      $author['fb_account'] = $this->author->facebook ?? null;
    }
    if (setting('is_display_instagram')) {
      $author['inst_account'] = $this->author->instagram ?? null;
    }
    if (setting('is_display_youtube')) {
      $author['youtb_account'] = $this->author->linkedin ?? null;
    }
    if (setting('is_display_linkedin')) {
      $author['link_account'] = $this->author->youtube ?? null;
    }
    if (setting('is_display_pinterest')) {
      $author['print_account'] = $this->author->pinterest ?? null;
    }
    if (setting('is_display_twitter')) {
      $author['twitter_account'] = $this->author->twitter ?? null;
    }
    $ratings = $this->ratings()->where('is_published', true)->orderBy('id', 'DESC');
    return [
      'id' => $this->id,
      'title' => $this->name,
      'category' => new CategoryResource($this->category),
      'description' => $this->description,
      'content' => $content,
      'url' => $this->url,
      'slug' => Common::buildSlug($this->url),
      'image' => $this->image ? $this->image->url : null,
      'image_alt' => $this->image ? $this->image->alt : null,
      'image_des' => $this->image ? $this->image->description : null,
      'author' => $author,
      'created_at' => $this->created_at->toFormattedDateString(),
      'comments_count' => $this->comments()->where('is_published', true)->count(),
      'count_like' => $this->like,
      'viewed' => $this->view_count,
      'meta' => new SeoResource($this->seometa, $this),
      'content_list' => ContentListResource::collection($this->contentList()->whereRaw('name != ""')->get() ?? []),
      'ratings' => [
        'items' => RatingResource::collection($ratings->paginate(setting('item_on_page', 10)) ?? [])->response()->getData(true),
        'avg' => round($ratings->sum('stars')/ ($ratings->count() > 0 ? $ratings->count() : 1), 1),
        'best_rating' => $ratings->max('stars') ?? 0,
        'total_rating' => $ratings->count(),
      ],
      'created_at_iso' => $this->created_at->toIso8601String(),
      'updated_at_iso' => $this->updated_at->toIso8601String(),
      'published_at_iso' => $this->published_at ? $this->published_at->toIso8601String() : null,
      'faqs' => FaqResource::collection($this->faqs),
    ];
  }
}
