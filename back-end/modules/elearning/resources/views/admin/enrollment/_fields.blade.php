@select(['name' => 'status', 'label' => __('elearning::enrollment.status'), 'options' => [
    ['value' => 'pending', 'label' => __('Pending')],
    ['value' => 'active', 'label' => __('Active')],
    ['value' => 'completed', 'label' => __('Completed')],
    ['value' => 'expired', 'label' => __('Expired')],
    ['value' => 'cancelled', 'label' => __('Cancelled')],
]])
<div class="row">
    <div class="col-md-4">
        @input(['name' => 'price_paid', 'label' => __('elearning::enrollment.price_paid'), 'mask' => 'money', 'disabled' => true])
    </div>
    <div class="col-md-4">
        @input(['name' => 'original_price', 'label' => __('elearning::enrollment.original_price'), 'mask' => 'money', 'disabled' => true])
    </div>
    <div class="col-md-4">
        @input(['name' => 'discount_amount', 'label' => __('elearning::enrollment.discount_amount'), 'mask' => 'money', 'disabled' => true])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @input(['name' => 'payment_method', 'label' => __('elearning::enrollment.payment_method'), 'disabled' => true])
    </div>
    <div class="col-md-6">
        @input(['name' => 'transaction_id', 'label' => __('elearning::enrollment.transaction_id'), 'disabled' => true])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @input(['name' => 'enrolled_at', 'label' => __('elearning::enrollment.enrolled_at'), 'disabled' => true])
    </div>
    <div class="col-md-6">
        @input(['name' => 'expires_at', 'label' => __('elearning::enrollment.expires_at'), 'disabled' => true])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @input(['name' => 'completion_percentage', 'label' => __('elearning::enrollment.completion_percentage'), 'disabled' => true])
    </div>
    <div class="col-md-6">
        @input(['name' => 'completed_at', 'label' => __('elearning::enrollment.completed_at'), 'disabled' => true])
    </div>
</div>
