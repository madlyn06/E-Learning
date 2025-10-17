<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ecommerce\Enums\OrderStatus;

class OrderDetailResource extends JsonResource
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
            'order_no' => $this->order_no,
            'email' => $this->email,
            'status' => OrderStatus::getLabel($this->status),
            'note' => $this->note,
            'total_amount' => format_money($this->total_price),
            'shipping_address' => json_decode($this->shipping_address),
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'order_items' => OrderItemDetailResource::collection($this->orderItems ?? []),
            'discount_code' => $this->discount_code,
            'discount_amount' => format_money($this->discount_amount),
        ];
    }
}
