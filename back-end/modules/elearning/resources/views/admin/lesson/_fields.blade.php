@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#elearning_lesson_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#elearning_lesson_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="elearning_lesson_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('elearning::lesson.name')])
                @textarea(['name' => 'description', 'label' => __('elearning::lesson.description')])
                @editor(['name' => 'content', 'label' => __('elearning::lesson.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                @checkbox(['name' => 'is_active', 'label' => __('elearning::lesson.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="elearning_lesson_Seo">
        @seo
    </div>
</div>
