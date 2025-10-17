@translatableAlert

<div class="row">
    <div class="col-12 col-md-9">
        <div class="form-horizontal">
            @if(config('cms.slider.media_type') && config('cms.slider.media_type') == true)
                @mediamanager(['name' => 'image', 'label' => __('slider::slider-item.image'), 'conversion' => ''])
            @else
                @mediafile(['name' => 'image', 'label' => __('slider::slider-item.image'), 'conversion' => ''])
            @endif
            @input(['name' => 'name', 'label' => __('slider::slider-item.name')])
            @input(['name' => 'link', 'label' => __('slider::slider-item.link')])
            @textarea(['name' => 'description', 'label' => __('slider::slider-item.description')])
            @editor(['name' => 'content', 'label' => __('slider::slider-item.content')])
        </div>
    </div>
    <div class="col-12 col-md-3">
        @translatable

        @checkbox(['name' => 'is_active', 'label' => __('slider::slider-item.is_active')])
        @input(['name' => 'sort_order', 'label' => __('slider::slider-item.sort_order')])
    </div>
</div>
