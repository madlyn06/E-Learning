<?php

namespace Modules\Elearning\Services\Payment;

use Modules\Elearning\Models\Payment;
use Illuminate\Support\Facades\Log;

class VnPayGateway implements PaymentGatewayInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function initializePayment(Payment $payment): array
    {
        return [];
    }

    public function verifyPayment(Payment $payment): array
    {
        return [];        
    }

    public function handleCallback(array $data): array
    {
        return [];
    }
}
