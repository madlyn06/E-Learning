<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class PostTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => Common::buildSlug($this->url),
        ];
    }
}
