<?php

namespace Modules\Manage\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Seo\Http\Resources\SeoResource;

class AdminResource extends JsonResource
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
    return [
      'name' => $this->name,
      'slug' => $this->slug,
      'email' => $this->email,
      'phone' => $this->phone,
      'title' => $this->title,
      'address' => $this->address,
      'description' => $this->description,
      'avatar' => $avatar,
      'avatar_alt' => $avatar_alt,
      'avatar_des' => $avatar_des,
      'facebook' => $this->facebook,
      'instagram' => $this->instagram,
      'youtube' => $this->youtube,
      'pinterest' => $this->pinterest,
      'linkedin' => $this->linkedin,
      'twitter' => $this->twitter,
      'meta' => new SeoResource($this->seometa, $this),
    ];
  }
}
