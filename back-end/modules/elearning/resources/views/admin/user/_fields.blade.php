<div class="row">
    <div class="col-md-6">
        @input([
            'name' => 'name',
            'label' => __('elearning::teacher.name')
        ])

        @input([
            'name' => 'headline',
            'label' => __('elearning::teacher.headline'),
        ])
    </div>

    <div class="col-md-6">
        @checkbox(['name' => 'is_enable', 'label' => '', 'placeholder' => 'Is active'])
        @input([
            'name' => 'website',
            'label' => __('elearning::teacher.website'),
            'type' => 'url'
        ])
    </div>
</div>

<div class="row">
    <div class="col-6">
        @textarea([
            'name' => 'description',
            'label' => __('elearning::teacher.description'),
            'rows' => 5
        ])
    </div>
     <div class="col-6">
        @textarea([
            'name' => 'bio',
            'label' => __('elearning::teacher.bio'),
            'rows' => 5
        ])
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @input([
            'name' => 'facebook',
            'label' => __('elearning::teacher.facebook'),
        ])

        @input([
            'name' => 'twitter',
            'label' => __('elearning::teacher.twitter'),
        ])
    </div>
    <div class="col-md-6">
        @input([
            'name' => 'linkedin',
            'label' => __('elearning::teacher.linkedin'),
        ])

        @input([
            'name' => 'youtube',
            'label' => __('elearning::teacher.youtube'),
        ])
    </div>
</div>
