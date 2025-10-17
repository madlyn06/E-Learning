<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemDetailResource extends JsonResource
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
      'product_name' => $this->product_name,
      'product_image' => $this->product->image ? $this->product->image->url : null,
      'quantity' => $this->quantity,
      'price' => format_money($this->price),
      'total_price' => format_money($this->price * $this->quantity),
    ];
  }
}
