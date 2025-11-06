<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'minimum_purchase' => $this->minimum_purchase,
            'maximum_discount' => $this->maximum_discount,
            'starts_at' => $this->starts_at ? $this->starts_at->format('Y-m-d H:i:s') : null,
            'expires_at' => $this->expires_at ? $this->expires_at->format('Y-m-d H:i:s') : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
