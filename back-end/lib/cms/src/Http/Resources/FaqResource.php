<?php

namespace Newnet\Cms\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
      'question' => $this->question,
      'answer' => $this->answer,
      'created_at' => $this->created_at->format('Y-m-d H:s:i'),
    ];
  }
}
