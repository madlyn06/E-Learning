<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'logo' => $this->logo ? $this->logo->url : null,
            'is_active' => $this->is_active,
            'display_order' => $this->display_order,
        ];
    }
}
