@input(['name' => 'id', 'type' => 'hidden'])

<div class="row">
    <div class="col-md-6">
        @input([
            'name' => 'name',
            'label' => __('elearning::membership.name'),
            'value' => $item->name ?? old('name'),
            'required' => true
        ])

        @textarea([
            'name' => 'description',
            'label' => __('elearning::membership.description'),
            'value' => $item->description ?? old('description'),
            'rows' => 3
        ])

        @input([
            'name' => 'price',
            'type' => 'number',
            'label' => __('elearning::membership.price'),
            'value' => $item->price ?? old('price'),
            'required' => true,
            'step' => '0.01',
            'min' => '0'
        ])

        @select([
            'name' => 'interval',
            'label' => __('elearning::membership.interval'),
            'options' => [
                'monthly' => __('elearning::membership.intervals.monthly'),
                'quarterly' => __('elearning::membership.intervals.quarterly'),
                'annually' => __('elearning::membership.intervals.annually'),
                'lifetime' => __('elearning::membership.intervals.lifetime')
            ],
            'value' => $item->interval ?? old('interval', 'monthly'),
            'required' => true
        ])
    </div>

    <div class="col-md-6">
        @input([
            'name' => 'course_access_limit',
            'type' => 'number',
            'label' => __('elearning::membership.course_access_limit'),
            'value' => $item->course_access_limit ?? old('course_access_limit', 0),
            'min' => 0,
            'help' => __('elearning::membership.course_access_limit_help')
        ])

        @input([
            'name' => 'duration_days',
            'type' => 'number',
            'label' => __('elearning::membership.duration_days'),
            'value' => $item->duration_days ?? old('duration_days', 30),
            'required' => true,
            'min' => 1,
            'help' => __('elearning::membership.duration_days_help')
        ])

        @select([
            'name' => 'status',
            'label' => __('elearning::membership.status'),
            'options' => [
                'active' => __('elearning::membership.statuses.active'),
                'inactive' => __('elearning::membership.statuses.inactive')
            ],
            'value' => $item->status ?? old('status', 'active'),
            'required' => true
        ])

        @checkbox([
            'name' => 'is_featured', 
            'label' => __('elearning::membership.is_featured'),
            'checked' => isset($item->is_featured) ? $item->is_featured : old('is_featured', false),
            'help' => __('elearning::membership.is_featured_help')
        ])
    </div>
</div>

<div class="row">
    <div class="col-12">
        @textarea([
            'name' => 'features',
            'label' => __('elearning::membership.features'),
            'value' => $item->features ?? old('features'),
            'rows' => 5,
            'help' => __('elearning::membership.features_help')
        ])
    </div>
</div>
