@translatableAlert

<ul class="nav nav-tabs scrollable">
  <li class="nav-item">
    <a class="nav-link active save-tab" data-toggle="pill" href="#cmsPostInfo">
      {{ __('cms::post.tabs.info') }}
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
      <div class="col-12 col-md-12">
        @input(['name' => 'name', 'label' => __('cms::story.name'), 'required' => true])
        @slug(['name' => 'slug', 'label' => __('cms::story.slug'), 'slugFrom' => '#name', 'required' => true])
        @select(['name' => 'post_id', 'allowClear' => false, 'label' => __('cms::story.post_id'), 'options' => get_all_posts_options(), 'required' => true])
        @textarea(['name' => 'description', 'label' => __('cms::story.description')])
        @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('cms::story.is_active'), 'default' => true])
        @mediamanager(['name' => 'image', 'label' => __('cms::story.image'), 'conversion' => ''])
        @mediamanager(['name' => 'audio', 'label' => __('cms::story-item.audio'), 'conversion' => ''])
      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="cmsPostSeo">
    @seo(['col' => 12])
  </div>
</div>
