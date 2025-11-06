<?php

declare(strict_types=1);

namespace Modules\Elearning\Services\Payment;

use Modules\Elearning\Models\Payment;
use Illuminate\Support\Facades\Log;

class MomoGateway implements PaymentGatewayInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Initialize a payment with Momo
     * 
     * @param Payment $payment
     * @return array Payment details including redirect URL
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            // In a real implementation, this would call the Momo API
            // For now, we'll simulate creating a payment request

            // Generate payload for Momo
            $orderInfo = "Payment for " . ($payment->course_id ? "course" : "membership");
            $amount = (int) $payment->amount;
            $orderId = $payment->reference_id;
            $redirectUrl = route('elearning.payment.callback', ['gateway' => 'momo']);
            $ipnUrl = route('elearning.payment.ipn', ['gateway' => 'momo']);
            
            $requestId = uniqid();
            $requestType = "captureWallet";
            $extraData = base64_encode(json_encode([
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
            ]));
            
            $rawSignature = "accessKey=" . ($this->config['access_key'] ?? 'test_access_key') .
                "&amount=" . $amount .
                "&extraData=" . $extraData .
                "&ipnUrl=" . $ipnUrl .
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&partnerCode=" . ($this->config['partner_code'] ?? 'MOMOXYZ') .
                "&redirectUrl=" . $redirectUrl .
                "&requestId=" . $requestId .
                "&requestType=" . $requestType;
            
            // Simulating HMAC SHA256 signature
            $signature = hash_hmac('sha256', $rawSignature, $this->config['secret_key'] ?? 'test_secret_key');
            
            $payload = [
                'partnerCode' => $this->config['partner_code'] ?? 'MOMOXYZ',
                'partnerName' => $this->config['partner_name'] ?? 'Elearning System',
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'requestType' => $requestType,
                'extraData' => $extraData,
                'signature' => $signature,
                'lang' => 'vi',
            ];
            
            // Simulate API response
            $paymentUrl = "https://example-momo.com/pay?token=" . uniqid();
            
            // Set expiration time (15 minutes from now)
            $expiresAt = now()->addMinutes(15);
            
            // Update payment record
            $payment->update([
                'transaction_data' => [
                    'request_id' => $requestId,
                    'payload' => $payload,
                ],
                'payment_url' => $paymentUrl,
                'expires_at' => $expiresAt,
                'qr_code' => null, // Momo might provide QR later, but primarily uses URL redirect
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'reference_id' => $payment->reference_id,
                'payment_url' => $paymentUrl,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
                'payment_method' => 'momo',
                'message' => 'Payment URL generated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Momo payment initialization failed: ' . $e->getMessage(), [
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
     * Verify the status of a payment
     * 
     * @param Payment $payment
     * @return array Payment verification result
     */
    public function verifyPayment(Payment $payment): array
    {
        try {
            // In a real implementation, you would check with Momo API
            // For now, we'll return a simulated response
            
            // Check if payment has expired
            if ($payment->expires_at && now()->isAfter($payment->expires_at)) {
                $payment->update(['status' => 'expired']);
                
                return [
                    'success' => false,
                    'status' => 'expired',
                    'message' => 'Payment has expired',
                ];
            }
            
            // Simulate checking payment status with Momo
            $transactionData = $payment->transaction_data;
            $requestId = uniqid();
            $orderId = $payment->reference_id;
            
            $rawSignature = "accessKey=" . ($this->config['access_key'] ?? 'test_access_key') .
                "&orderId=" . $orderId .
                "&partnerCode=" . ($this->config['partner_code'] ?? 'MOMOXYZ') .
                "&requestId=" . $requestId;
            
            // Simulating HMAC SHA256 signature
            $signature = hash_hmac('sha256', $rawSignature, $this->config['secret_key'] ?? 'test_secret_key');
            
            $checkStatusPayload = [
                'partnerCode' => $this->config['partner_code'] ?? 'MOMOXYZ',
                'requestId' => $requestId,
                'orderId' => $orderId,
                'signature' => $signature,
                'lang' => 'vi',
            ];
            
            // For demo purposes, we'll randomly determine if payment was successful
            $isPaid = (rand(0, 1) == 1);
            
            if ($isPaid) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => 'MOMO-' . uniqid(),
                    'transaction_data' => array_merge($transactionData, [
                        'status_check' => [
                            'time' => now()->format('Y-m-d H:i:s'),
                            'result' => 'success',
                        ]
                    ]),
                    'notes' => 'Payment verified via status check',
                ]);
                
                return [
                    'success' => true,
                    'status' => 'completed',
                    'transaction_id' => $payment->transaction_id,
                    'message' => 'Payment has been completed',
                ];
            }
            
            return [
                'success' => true,
                'status' => 'pending',
                'message' => 'Payment is still pending',
            ];
        } catch (\Exception $e) {
            Log::error('Momo payment verification failed: ' . $e->getMessage(), [
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
     * Process a callback/webhook from the payment gateway
     * 
     * @param array $data Callback data
     * @return array Processed result
     */
    public function handleCallback(array $data): array
    {
        try {
            // Extract data from callback
            $orderId = $data['orderId'] ?? null;
            $resultCode = $data['resultCode'] ?? 99;
            $transId = $data['transId'] ?? null;
            $amount = $data['amount'] ?? 0;
            $extraData = isset($data['extraData']) ? json_decode(base64_decode($data['extraData']), true) : [];
            
            if (!$orderId) {
                return [
                    'success' => false,
                    'message' => 'Missing order ID in callback data',
                ];
            }
            
            // Find the payment
            $payment = Payment::where('reference_id', $orderId)->first();
            
            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment not found',
                ];
            }
            
            // Verify signature (in a real implementation)
            // $isValidSignature = $this->verifySignature($data);
            $isValidSignature = true; // For simulation
            
            if (!$isValidSignature) {
                $payment->update([
                    'notes' => 'Invalid signature in callback',
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Invalid signature',
                ];
            }
            
            // Process based on result code
            if ($resultCode == 0) { // Success
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $transId,
                    'transaction_data' => array_merge($payment->transaction_data ?? [], [
                        'callback_data' => $data,
                        'callback_time' => now()->format('Y-m-d H:i:s'),
                    ]),
                    'notes' => 'Payment completed via Momo callback',
                ]);
                
                return [
                    'success' => true,
                    'payment_id' => $payment->id,
                    'status' => 'completed',
                    'message' => 'Payment completed successfully',
                ];
            } else {
                $payment->update([
                    'status' => 'failed',
                    'transaction_data' => array_merge($payment->transaction_data ?? [], [
                        'callback_data' => $data,
                        'callback_time' => now()->format('Y-m-d H:i:s'),
                    ]),
                    'notes' => 'Payment failed: Result code ' . $resultCode,
                ]);
                
                return [
                    'success' => false,
                    'payment_id' => $payment->id,
                    'status' => 'failed',
                    'message' => 'Payment failed with code: ' . $resultCode,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Momo callback processing failed: ' . $e->getMessage(), [
                'callback_data' => $data,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to process callback: ' . $e->getMessage(),
            ];
        }
    }
}
