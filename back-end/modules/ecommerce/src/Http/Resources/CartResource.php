<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Newnet\Core\Utils\Common;

class CartResource extends JsonResource
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
      'id' => $this->id,
      'product_id' => $this->product_id,
      'product_name' => $this->product->name,
      'product_url' => Common::buildSlug($this->product->url),
      'product_image' => $this->product->image ? $this->product->image->url : null,
      'quantity' => $this->quantity,
      'price' => format_money($this->price),
      'price_no_format' => $this->price,
      'total_price' => format_money($this->price * $this->quantity),
    ];
  }
}
