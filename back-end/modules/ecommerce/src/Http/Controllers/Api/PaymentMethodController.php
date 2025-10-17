<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Http\Resources\PaymentMethodResource;
use Modules\Ecommerce\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
  public function getAllActivePaymentMethods()
  {
    return PaymentMethodResource::collection(PaymentMethod::whereIsActive(true)->get());
  }
}
