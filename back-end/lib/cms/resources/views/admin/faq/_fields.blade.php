@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPageInfo">
            {{ __('cms::page.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsPageSeo">
            {{ __('cms::page.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsPageInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                <input type="hidden" value="{{ $post->id }}" name="post_id"/>
                @input(['name' => 'question', 'label' => __('cms::faq.question'), 'required' => true])
                @textarea(['name' => 'answer', 'label' => __('cms::faq.answer'), 'autoResize' => true, 'required' => true])
                @input(['name' => 'position', 'label' => __('cms::faq.position'), 'default' => 0])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::page.is_active'), 'default' => true])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="cmsPageSeo">
        @seo
    </div>
</div>
