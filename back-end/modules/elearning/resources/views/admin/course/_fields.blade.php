@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#elearning_course_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#elearning_course_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="elearning_course_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('elearning::course.name')])
                @input(['name' => 'price', 'label' => __('elearning::course.price'), 'mask' => 'money'])
                @input(['name' => 'sale_price', 'label' => __('elearning::course.sale_price'), 'mask' => 'money'])
                @textarea(['name' => 'summary', 'label' => __('elearning::course.description')])
                @editor(['name' => 'content', 'label' => __('elearning::course.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('elearning::course.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="elearning_course_Seo">
        @seo
    </div>
</div>
