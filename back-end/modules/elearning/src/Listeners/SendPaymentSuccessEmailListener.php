<?php

namespace Modules\Elearning\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Elearning\Events\PaymentCompleted;
use Modules\Elearning\Services\MailServiceInterface;

class SendPaymentSuccessEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The mail service instance.
     *
     * @var \Modules\Elearning\Services\MailServiceInterface
     */
    protected $mailService;

    /**
     * Create the event listener.
     *
     * @param  \Modules\Elearning\Services\MailServiceInterface  $mailService
     * @return void
     */
    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     *
     * @param  \Modules\Elearning\Events\PaymentCompleted  $event
     * @return void
     */
    public function handle(PaymentCompleted $event)
    {
        $payment = $event->payment;
        $user = $payment->user;
        
        // Load the related course or membership
        if ($payment->course_id) {
            $payment->load('course');
            $item = $payment->course;
            $itemType = 'course';
            $itemName = $item->title;
            $itemUrl = route('elearning.courses.show', $item->id);
        } elseif ($payment->membership_id) {
            $payment->load('membership');
            $item = $payment->membership;
            $itemType = 'membership';
            $itemName = $item->name;
            $itemUrl = route('elearning.dashboard');
        } else {
            return; // No item to link to
        }

        // Prepare email data
        $data = [
            'user' => $user,
            'payment' => $payment,
            'item_type' => $itemType,
            'item_name' => $itemName,
            'item_url' => $itemUrl,
            'amount' => number_format($payment->amount, 0, ',', '.') . ' ' . $payment->currency,
            'payment_date' => $payment->updated_at->format('d/m/Y H:i:s'),
            'reference_id' => $payment->reference_id
        ];

        // Send email
        $this->mailService->sendPaymentSuccessEmail($user->email, $data);
    }
}
