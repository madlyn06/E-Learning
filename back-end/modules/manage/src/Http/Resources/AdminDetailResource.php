<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Cms\Utils\StringUtils;
use Newnet\Seo\Http\Resources\SeoResource;

class AdminDetailResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $avatar = null;
    $avatar_alt = $avatar_des = null;
    if (is_string($this->avatar)) {
      $avatar = config('app.url').$this->avatar;
      $avatar_alt = $avatar_des = $this->name;
    } elseif(is_object($this->avatar)) {
      $avatar = $this->avatar->url;
      $avatar_alt = $this->avatar->alt ?? $this->name;
      $avatar_des = $this->avatar->description ?? $this->name;
    }

    $social = [];

    if (setting('is_display_facebook')) {
      $social['fb_account'] = $this->facebook;
    }
    if (setting('is_display_instagram')) {
      $social['inst_account'] = $this->instagram;
    }
    if (setting('is_display_youtube')) {
      $social['youtb_account'] = $this->youtube;
    }
    if (setting('is_display_linkedin')) {
      $social['link_account'] = $this->linkedin;
    }
    if (setting('is_display_pinterest')) {
      $social['print_account'] = $this->pinterest;
    }
    if (setting('is_display_twitter')) {
      $social['twitter_account'] = $this->twitter;
    }

    return [
      'name' => $this->name,
      'slug' => $this->slug,
      'email' => $this->email,
      'title' => $this->title,
      'description' => $this->description,
      'content' => $this->content ? StringUtils::replaceImgElement($this->content) : $this->content,
      'meta' => new SeoResource($this->seometa, $this),
      'avatar' => $avatar,
      'avatar_alt' => $avatar_alt,
      'avatar_des' => $avatar_des,
      'social' => $social,
    ];
  }
}
