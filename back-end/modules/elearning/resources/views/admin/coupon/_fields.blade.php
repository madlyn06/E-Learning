@input(['name' => 'id', 'type' => 'hidden'])

<div class="row">
    <div class="col-md-6">
        @input([
            'name' => 'code',
            'label' => __('elearning::coupon.code'),
            'value' => $item->code ?? old('code'),
            'required' => true
        ])

        @input([
            'name' => 'description',
            'label' => __('elearning::coupon.description'),
            'value' => $item->description ?? old('description')
        ])

        @select([
            'name' => 'type',
            'label' => __('elearning::coupon.type'),
            'options' => [
                'percentage' => __('elearning::coupon.types.percentage'),
                'fixed' => __('elearning::coupon.types.fixed')
            ],
            'value' => $item->type ?? old('type', 'percentage'),
            'required' => true
        ])

        @input([
            'name' => 'value',
            'type' => 'number',
            'label' => __('elearning::coupon.value'),
            'value' => $item->value ?? old('value'),
            'required' => true,
            'step' => '0.01',
            'min' => '0'
        ])
    </div>

    <div class="col-md-6">
        @input([
            'name' => 'max_uses',
            'type' => 'number',
            'label' => __('elearning::coupon.max_uses'),
            'value' => $item->max_uses ?? old('max_uses', 0),
            'min' => 0,
            'help' => __('elearning::coupon.max_uses_help')
        ])

        @input([
            'name' => 'used_count',
            'type' => 'number',
            'label' => __('elearning::coupon.used_count'),
            'value' => $item->used_count ?? old('used_count', 0),
            'min' => 0,
            'readonly' => isset($item),
            'disabled' => isset($item),
            'help' => __('elearning::coupon.used_count_help')
        ])

        @input([
            'name' => 'start_date',
            'type' => 'datetime-local',
            'label' => __('elearning::coupon.start_date'),
            'value' => isset($item->start_date) ? $item->start_date->format('Y-m-d\TH:i') : old('start_date')
        ])

        @input([
            'name' => 'end_date',
            'type' => 'datetime-local',
            'label' => __('elearning::coupon.end_date'),
            'value' => isset($item->end_date) ? $item->end_date->format('Y-m-d\TH:i') : old('end_date')
        ])
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @select([
            'name' => 'courses[]',
            'label' => __('elearning::coupon.courses'),
            'options' => $courses ?? [],
            'value' => isset($item) ? $item->courses->pluck('id')->toArray() : old('courses', []),
            'multiple' => true,
            'help' => __('elearning::coupon.courses_help')
        ])
    </div>
    <div class="col-md-6">
        @select([
            'name' => 'status',
            'label' => __('elearning::coupon.status'),
            'options' => [
                'active' => __('elearning::coupon.statuses.active'),
                'inactive' => __('elearning::coupon.statuses.inactive'),
                'expired' => __('elearning::coupon.statuses.expired')
            ],
            'value' => $item->status ?? old('status', 'active'),
            'required' => true
        ])
    </div>
</div>
