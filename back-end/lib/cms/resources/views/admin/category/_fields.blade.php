@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#cmsCategoryInfo">
            {{ __('cms::category.tabs.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#cmsCategorySeo">
            {{ __('cms::category.tabs.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="cmsCategoryInfo">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('cms::category.name'), 'required' => true])
                @textarea(['name' => 'description', 'label' => __('cms::category.description')])
                @editor(['name' => 'content', 'label' => __('cms::category.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable

                @checkbox(['name' => 'is_active', 'label' => __('cms::category.is_active'), 'default' => true])
                @select(['name' => 'parent_id', 'label' => __('cms::category.parent'), 'options' => get_category_parent_options()])
                @if(config('cms.cms.media_manager') && config('cms.cms.media_manager') == true)
                    @mediamanager(['name' => 'image', 'label' => __('cms::category.image')])
                @else
                    @mediafile(['name' => 'image', 'label' => __('cms::category.image')])
                @endif
                @input(['name' => 'icon', 'label' => __('cms::category.icon'),
                'helper' => "<a href='#' data-toggle='modal' data-target='#modalIcon'><i class='fas fa-question-circle mr-2'></i>Các icon hỗ trợ</a> "])

            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="cmsCategorySeo">
        @seo
    </div>
</div>

<div id="modalIcon" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ __('manage::service.modal_title') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
            
            <h6>Sử dụng các icon bên dưới hoặc xem thêm <a href="http://thetheme.io/theadmin/content/icons-themify.html" target="_blank" style="color: red">tại đây</a></h6>
            <hr>
                @include('cms::admin.category.icon')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
