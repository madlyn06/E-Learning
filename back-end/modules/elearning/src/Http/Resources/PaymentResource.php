<?php

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference_id' => $this->reference_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_url' => $this->payment_url,
            'qr_code' => $this->qr_code,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'payment_method' => new PaymentMethodResource($this->whenLoaded('paymentMethod')),
            'course' => $this->when($this->course_id, function() {
                return [
                    'id' => $this->course->id,
                    'title' => $this->course->name,
                ];
            }),
            'membership' => $this->when($this->membership_id, function() {
                return [
                    'id' => $this->membership->id,
                    'name' => $this->membership->name,
                ];
            }),
        ];
    }
    
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        
        if (is_object($resource) && method_exists($resource, 'links')) {
            $collection->additional(['meta' => [
                'current_page' => $resource->currentPage(),
                'last_page' => $resource->lastPage(),
                'per_page' => $resource->perPage(),
                'total' => $resource->total(),
            ]]);
        }
        
        return $collection;
    }
}
