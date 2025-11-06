<?php

namespace Modules\Elearning\Services\Payment;

use Modules\Elearning\Models\Payment;
use Modules\Elearning\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Get the appropriate payment gateway for a payment method
     * 
     * @param string $gatewayCode
     * @return PaymentGatewayInterface
     * @throws \Exception
     */
    public function getGateway(string $gatewayCode): PaymentGatewayInterface
    {
        $paymentMethod = PaymentMethod::where('code', $gatewayCode)
            ->where('is_active', true)
            ->first();
            
        if (!$paymentMethod) {
            throw new \Exception("Payment method not found or inactive: {$gatewayCode}");
        }
        
        $config = $paymentMethod->config ?? [];
        
        switch ($gatewayCode) {
            case 'payos':
                return new VietQRGateway($config);
            case 'momo':
                return new MomoGateway($config);
            case 'vnpay':
                return new VnPayGateway($config);
            // Add more gateways as needed
            default:
                throw new \Exception("Unsupported payment gateway: {$gatewayCode}");
        }
    }
    
    /**
     * Create a new payment
     * 
     * @param array $data Payment data
     * @return Payment
     */
    public function createPayment(array $data): Payment
    {
        // Generate a unique reference ID
        $referenceId = Payment::generateReferenceId();
        
        // Create payment record
        $payment = Payment::create([
            'reference_id' => $referenceId,
            'user_id' => $data['user_id'],
            'course_id' => $data['course_id'] ?? null,
            'membership_id' => $data['membership_id'] ?? null,
            'payment_method_id' => $data['payment_method_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'VND',
            'status' => 'pending',
            'expires_at' => $data['expires_at'] ?? now()->addHours(1), // Default expiration
        ]);
        
        return $payment;
    }
    
    /**
     * Initialize a payment with the appropriate gateway
     * 
     * @param Payment $payment
     * @return array Payment initialization result
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            $paymentMethod = $payment->paymentMethod;
            
            if (!$paymentMethod) {
                throw new \Exception(message: "Payment method not found for payment ID: {$payment->id}");
            }
            
            $gateway = $this->getGateway($paymentMethod->code);
            
            return $gateway->initializePayment($payment);
        } catch (\Exception $e) {
            Log::error('Payment initialization failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            
            $payment->update([
                'status' => 'failed',
                'notes' => 'Payment initialization failed: ' . $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Verify a payment's status
     * 
     * @param Payment $payment
     * @return array Payment verification result
     */
    public function verifyPayment(Payment $payment): array
    {
        try {
            $paymentMethod = $payment->paymentMethod;
            
            if (!$paymentMethod) {
                throw new \Exception("Payment method not found for payment ID: {$payment->id}");
            }
            
            $gateway = $this->getGateway($paymentMethod->code);
            
            return $gateway->verifyPayment($payment);
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Handle a callback from a payment gateway
     * 
     * @param string $gatewayCode
     * @param array $data Callback data
     * @return array Callback processing result
     */
    public function handleCallback(string $gatewayCode, array $data): array
    {
        try {
            $gateway = $this->getGateway($gatewayCode);
            return $gateway->handleCallback($data);
        } catch (\Exception $e) {
            Log::error('Payment callback handling failed: ' . $e->getMessage(), [
                'gateway' => $gatewayCode,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to handle payment callback: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Process a completed payment
     * 
     * @param Payment $payment
     * @return void
     */
    public function processCompletedPayment(Payment $payment): void
    {
        // This method would handle post-payment processing
        // such as granting access to courses, activating memberships, etc.
        
        if ($payment->status !== 'completed') {
            return;
        }
        
        try {
            if ($payment->course_id) {
                // Grant access to course by activating enrollment
                $enrollmentService = app()->make('Modules\Elearning\Services\EnrollmentService');
                $enrollmentService->activateEnrollment($payment->reference_id);
            }
            
            if ($payment->membership_id) {
                // Activate membership
                // Implementation depends on your membership model
            }
            
            // Dispatch event for email notification
            event(new \Modules\Elearning\Events\PaymentCompleted($payment));
            
            // Log successful processing
            Log::info('Payment processed successfully', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Error processing completed payment: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
