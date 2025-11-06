<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Elearning\Http\Controllers\Api\BaseController;
use Modules\Elearning\Http\Resources\PaymentMethodResource;
use Modules\Elearning\Http\Resources\PaymentResource;
use Modules\Elearning\Models\Payment;
use Modules\Elearning\Models\PaymentMethod;
use Modules\Elearning\Services\Payment\PaymentService;

class PaymentController extends BaseController
{
    protected $paymentService;
    
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    /**
     * List available payment methods
     * 
     * @return JsonResponse
     */
    public function getPaymentMethods(): JsonResponse
    {
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('display_order')
            ->get();
        return $this->successResponse(PaymentMethodResource::collection($paymentMethods));
    }

    private function validatePaymentMethod(int $paymentMethodId): void
    {
        $paymentMethod = PaymentMethod::find($paymentMethodId);
        if (!$paymentMethod) {
            throw new \Exception(message: "Payment method not found");
        }

        if (!$paymentMethod->is_active) {
            throw new \Exception(message: "Payment method is not active");
        }
    }

    /**
     * Check the status of a payment
     * 
     * @param Request $request
     * @param string $referenceId
     * @return JsonResponse
     */
    public function checkPaymentStatus(Request $request, string $referenceId): JsonResponse
    {
        $payment = Payment::where('reference_id', $referenceId)
            ->where('user_id', $request->user()->id)
            ->with('paymentMethod')
            ->first();

        if (!$payment) {
            return $this->errorResponse('Payment not found');
        }
        
        // Check the payment status
        $result = $this->paymentService->verifyPayment($payment);
        
        // If payment is completed, process it
        if ($payment->status === 'completed') {
            $this->paymentService->processCompletedPayment($payment);
        }
        
        return $this->successResponse('Payment status checked', [
            'payment' => new PaymentResource($payment),
            'status' => $payment->status,
            'message' => $result['message'] ?? 'Payment status checked',
        ]);
    }
    
    /**
     * Get user payment history
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaymentHistory(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');
        
        $query = Payment::where('user_id', $request->user()->id)
            ->with(['paymentMethod', 'course', 'membership'])
            ->latest();
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $payments = $query->paginate($perPage);
        
        return $this->successResponse('Payment history fetched successfully', PaymentResource::collection($payments));
    }
}
