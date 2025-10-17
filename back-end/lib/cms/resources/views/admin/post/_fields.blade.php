@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPostInfo">
            {{ __('cms::post.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsList">
            {{ __('cms::post.tabs.list') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsComment">
            {{ __('cms::post.tabs.comment') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsFaq">
            {{ __('cms::post.tabs.faq') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsRating">
            {{ __('cms::post.tabs.rating') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsPostSeo">
            {{ __('cms::post.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsPostInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::post.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('cms::post.description'), 'autoResize' => true])
                @editor(['name' => 'content', 'label' => __('cms::post.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('cms::post.is_active'), 'default' => true])
                @checkbox(['name' => 'is_sticky', 'label' => '', 'placeholder' => __('cms::post.is_sticky')])
                @checkbox(['name' => 'is_created_story', 'label' => '', 'placeholder' => __('cms::post.is_created_story')])
                @checkbox(['name' => 'append_internal_link', 'label' => '', 'placeholder' => __('cms::post.append_internal_link'),])
                @input(['name' => 'sort_order', 'label' => __('cms::post.sort_order')])

                <div class="allway-open-sumoselect">
                    @sumoselect(['name' => 'categories', 'label' => __('cms::post.category'), 'multiple' => true, 'options' => get_category_parent_options()])
                </div>
                @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                @mediamanager(['name' => 'image', 'label' => __('cms::post.image')])
                @else
                @mediafile(['name' => 'image', 'label' => __('cms::post.image')])
                @endif
                @tags
                @tags(['name' => 'keywords', 'label' => 'Từ khoá', 'type' => \Newnet\Cms\Models\Keyword::class])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="cmsList">
        @include('cms::admin.post.list', ['items' => $item->contentList, 'post' => $item])
    </div>

    <div class="tab-pane fade" id="cmsComment">
        @if ($item->comments->count() > 0 )
            @include('cms::admin.post.comment', ['items' => $item->comments()->withDepth()->defaultOrder()->get(), 'post' => $item])
        @else
            <div class="alert alert-warning">Bài viết chưa có bình luận nào.</div>
        @endif
    </div>

    <div class="tab-pane fade" id="cmsFaq">
        @if ($item->id)
            @include('cms::admin.post.faq', ['items' => $item->faqs, 'post' => $item])
        @else
            <div class="alert alert-warning">Vui lòng tạo bài viết trước khi tạo FAQ.</div>
        @endif
    </div>

    <div class="tab-pane fade" id="cmsRating">
        @if($item->ratings->count() > 0)
            @include('cms::admin.post.rating', ['items' => $item->ratings, 'post' => $item])
        @else
            <div class="alert alert-warning">Bài viết chưa có đánh giá nào.</div>
        @endif
    </div>

    <div class="tab-pane fade" id="cmsPostSeo">
        @seo
    </div>
</div>
