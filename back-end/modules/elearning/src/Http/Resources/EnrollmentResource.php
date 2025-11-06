<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Elearning\Support\CommonHelper;

class EnrollmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'course' => $this->whenLoaded('course', function() {
                return [
                    'id' => $this->course->id,
                    'name' => $this->course->name,
                ];
            }),
            'coupon' => $this->whenLoaded('coupon', function() {
                return [
                    'id' => $this->coupon->id,
                    'code' => $this->coupon->code,
                    'name' => $this->coupon->name,
                ];
            }),
            'price_paid' => $this->price_paid,
            'original_price' => $this->original_price,
            'discount_amount' => $this->discount_amount,
            'payment_method' => $this->whenLoaded('paymentMethod', function() {
                return [
                    'id' => $this->paymentMethod->id,
                    'name' => $this->paymentMethod->name,
                ];
            }),
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'enrolled_at' => $this->enrolled_at ? $this->enrolled_at->format('Y-m-d H:i:s') : null,
            'expires_at' => $this->expires_at ? $this->expires_at->format('Y-m-d H:i:s') : null,
            'completion_percentage' => $this->completion_percentage,
            'completed_at' => $this->completed_at ? $this->completed_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
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
