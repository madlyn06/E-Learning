<?php

namespace Modules\Elearning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Elearning\Models\Payment;

class PaymentCompleted
{
    use Dispatchable, SerializesModels;

    /**
     * The payment instance.
     *
     * @var \Modules\Elearning\Models\Payment
     */
    public $payment;

    /**
     * Create a new event instance.
     *
     * @param  \Modules\Elearning\Models\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
