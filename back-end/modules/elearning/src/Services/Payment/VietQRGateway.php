<?php

declare(strict_types=1);

namespace Modules\Elearning\Services\Payment;

use Modules\Elearning\Models\Payment;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use PayOS\PayOS;

class VietQRGateway implements PaymentGatewayInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Initialize a payment with VietQR
     * 
     * @param Payment $payment
     * @return array Payment details including QR code
     */
    public function initializePayment(Payment $payment): array
    {
        try {
            // If the payment has an existing QR code that is not expired, return it
            if (
                $payment->expires_at && Carbon::parse($payment->expires_at)->isFuture()
                && ($payment->payment_url || $payment->qr_code)
            ) {
                return [
                    'success'        => true,
                    'payment_id'     => $payment->id,
                    'reference_id'   => $payment->reference_id,
                    'payment_method' => 'payos',
                    'checkout_url'   => $payment->payment_url,
                    'qr_code'        => $payment->qr_code, // PayOS returns data URL
                    'expires_at'     => Carbon::parse($payment->expires_at)->format('Y-m-d H:i:s'),
                    'message'        => 'Reused existing PayOS link (not expired)',
                ];
            }

            $orderCode = str_pad((string) $payment->id, 8, '0', STR_PAD_LEFT);
            $amount     = (int) $payment->amount;
            $desc       = $this->buildPayOSDescription($payment->reference_id);
            $returnUrl  = route('elearning.web.payment.success', ['gateway' => 'payos']);
            $cancelUrl  = route('elearning.web.payment.cancel', ['gateway' => 'payos']);

            // (optional) items displayed on the payment page of PayOS
            $items = $this->buildPayOSItemsFromPayment($payment);

            $createPayload = array_filter([
                'orderCode'   => $orderCode,
                'amount'      => $amount,
                'description' => $desc,
                'returnUrl'   => $returnUrl,
                'cancelUrl'   => $cancelUrl,
                'items'       => $items,
            ]);

            $client = $this->payosClient();
            $resp   = $client->createPaymentLink($createPayload);

            $paymentLinkId = Arr::get($resp, 'paymentLinkId');
            $checkoutUrl   = Arr::get($resp, 'checkoutUrl');
            $qrCode        = Arr::get($resp, 'qrCode');
            $status        = Arr::get($resp, 'status', 'PENDING');

            $expiresAt = $this->resolveExpiry($resp) ?? now()->addMinutes(30);

            $payment->update([
                'status'                => Str::lower($status) === 'paid' ? 'paid' : 'pending',
                'payos_payment_link_id' => $paymentLinkId,
                'payment_url'           => $checkoutUrl,
                'qr_image_base64'       => $qrCode, // to display QR immediately
                'expires_at'            => $expiresAt,
                'notes'                 => null,
                'transaction_data'      => array_merge((array) $payment->transaction_data, [
                    'payos' => [
                        'request'  => $createPayload,
                        'response' => $resp,
                    ],
                ]),
            ]);

            return [
                'success'        => true,
                'payment_id'     => $payment->id,
                'reference_id'   => $payment->reference_id,
                'payment_method' => 'payos',
                'checkout_url'   => $checkoutUrl,
                'qr_code'        => $qrCode,
                'expires_at'     => $expiresAt->format('Y-m-d H:i:s'),
                'message'        => 'PayOS link & QR generated successfully',
            ];
        } catch (\Throwable $e) {
            Log::error('initializePayment (PayOS) failed: ' . $e->getMessage(), [
                'payment_id'   => $payment->id ?? null,
                'reference_id' => $payment->reference_id ?? null,
            ]);

            $payment->update([
                'status' => 'failed',
                'notes'  => 'PayOS init failed: ' . $e->getMessage(),
                'transaction_data' => array_merge((array) $payment->transaction_data, [
                    'payos_error' => $e->getMessage(),
                ]),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to initialize PayOS: ' . $e,
            ];
        }
    }

    private function payosClient(): PayOS
    {
        if (!empty($this->config['partner_code'])) {
            return new PayOS(
                $this->config['client_id'],
                $this->config['api_key'],
                $this->config['checksum_key'],
                $this->config['partner_code']
            );
        }

        return new PayOS(
            $this->config['client_id'],
            $this->config['api_key'],
            $this->config['checksum_key']
        );
    }

    /**
     * Create description to display on PayOS (limit ~25 characters).
     */
    private function buildPayOSDescription(string $paymentId): string
    {
        // string 8 digits
        $desc = 'Order #' . str_pad($paymentId, 8, '0', STR_PAD_LEFT);
        return Str::limit($desc, 25, '');
    }

    /**
     * Convert items in payment (if any) to PayOS format.
     */
    private function buildPayOSItemsFromPayment(Payment $payment): ?array
    {
        return [
            [
                'name'     => 'Payment ' . $payment->reference_id,
                'quantity' => 1,
                'price'    => (int) $payment->amount,
            ]
        ];
    }

    /**
     * Get expires from response if present, return Carbon|null.
     */
    private function resolveExpiry(array $resp): ?Carbon
    {
        $expiredAt = Arr::get($resp, 'expiredAt') ?? Arr::get($resp, 'expireAt');
        if (!$expiredAt) return null;

        if (is_numeric($expiredAt)) {
            $ts = (int) $expiredAt;
            if ($ts > 9999999999) {
                return Carbon::createFromTimestampMs($ts);
            }
            return Carbon::createFromTimestamp($ts);
        }

        try {
            return Carbon::parse($expiredAt);
        } catch (\Throwable) {
            return null;
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
        // TODO: Implement this

        // Check if payment has expired
        if ($payment->expires_at && now()->isAfter($payment->expires_at)) {
            $payment->update(['status' => 'expired']);

            return [
                'success' => false,
                'status' => 'expired',
                'message' => 'Payment has expired',
            ];
        }

        // TODO: Implement this
        $isPaid = (rand(0, 1) == 1);

        if ($isPaid) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => 'VIETQR-' . uniqid(),
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
    }

    /**
     * Process a callback/webhook from the payment gateway
     * 
     * @param array $data Callback data
     * @return array Processed result
     */
    public function handleCallback(array $data): array
    {
        // VietQR doesn't typically use callbacks, but we'll implement this for consistency
        try {
            // Extract reference ID from callback data
            // TODO: Implement this 
            $referenceId = $data['reference_id'] ?? null;

            if (!$referenceId) {
                return [
                    'success' => false,
                    'message' => 'Missing reference ID in callback data',
                ];
            }

            // Find the payment
            $payment = Payment::where('reference_id', $referenceId)->first();

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Payment not found',
                ];
            }

            // Update payment status based on callback data
            $status = $data['status'] ?? 'pending';
            $transactionId = $data['transaction_id'] ?? null;

            $payment->update([
                'status' => $status,
                'transaction_id' => $transactionId,
                'transaction_data' => array_merge($payment->transaction_data ?? [], [
                    'callback_data' => $data,
                    'callback_time' => now()->format('Y-m-d H:i:s'),
                ]),
                'notes' => 'Updated via callback',
            ]);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'message' => 'Payment updated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('VietQR callback processing failed: ' . $e->getMessage(), [
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
