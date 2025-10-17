<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class KeywordableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->keywordable_id,
            'name' => $this->keyword->name,
            'slug' => $this->keyword->slug,
            'post_name' => $this->keywordable ? $this->keywordable->name : null,
            'post_url' => $this->keywordable ?  config('app.front_end_url').'/'.Common::buildSlug($this->keywordable->url) : null,
        ];
    }
}
