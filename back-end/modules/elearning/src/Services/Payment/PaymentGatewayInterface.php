<?php

namespace Modules\Elearning\Services\Payment;

use Modules\Elearning\Models\Payment;

/**
 * Interface for all payment gateway strategies
 */
interface PaymentGatewayInterface
{
    /**
     * Initialize a payment with the gateway
     * 
     * @param Payment $payment
     * @return array Payment details including redirectUrl or QR code
     */
    public function initializePayment(Payment $payment): array;
    
    /**
     * Verify the status of a payment
     * 
     * @param Payment $payment
     * @return array Payment verification result
     */
    public function verifyPayment(Payment $payment): array;
    
    /**
     * Process a callback/webhook from the payment gateway
     * 
     * @param array $data Callback data
     * @return array Processed result
     */
    public function handleCallback(array $data): array;
}
