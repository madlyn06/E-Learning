<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Elearning\Services\Payment\PaymentService;
use Modules\Elearning\Models\Payment;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle callback from payment gateway
     * 
     * @param Request $request
     * @param string $gateway
     * @return JsonResponse
     */
    public function handleCallback(Request $request, string $gateway): JsonResponse
    {
        try {
            $result = $this->paymentService->handleCallback($gateway, $request->all());
            
            if (!$result['success']) {
                return $this->errorResponse($result['message']);
            }
            
            // If payment ID is returned, process the completed payment
            if (isset($result['payment_id']) && isset($result['status']) && $result['status'] === 'completed') {
                $payment = Payment::find($result['payment_id']);
                if ($payment) {
                    $this->paymentService->processCompletedPayment($payment);
                }
            }
            
            return $this->successResponse($result['message']);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to process callback: ' . $e->getMessage());
        }
    }
}