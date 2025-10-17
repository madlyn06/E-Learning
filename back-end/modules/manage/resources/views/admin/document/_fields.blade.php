@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#manage_team_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#manage_file_Seo">
            {{ __('Seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="manage_file_Info">
        <div class="row">
            <div class="col-12 col-md-9">
                @input(['name' => 'name', 'label' => __('manage::document.name'), 'required' => true])
                @input(['name' => 'company_name', 'label' => __('manage::document.company_name'), 'required' => true])
                @input(['name' => 'file_version', 'label' => __('manage::document.file_version'), 'required' => true])
                @input(['name' => 'file_size', 'label' => __('manage::document.file_size'), 'required' => true])
                @input(['name' => 'download_url', 'label' => __('manage::document.download_url'), 'required' => true])
                @input(['name' => 'post_url', 'label' => __('manage::document.post_url')])
                @input(['name' => 'required', 'label' => __('manage::document.required')])
                @dateinput(['name' => 'published_date', 'label' => __('manage::document.published_date')])
                @textarea(['name' => 'description', 'label' => __('manage::document.description')])
                @editor(['name' => 'content', 'label' => __('manage::document.content')])
            </div>
            <div class="col-12 col-md-3">
                @translatable
                <div class="allway-open-sumoselect">
                    @sumoselect(['name' => 'file_categories', 'label' => __('manage::document.file_categories'), 'multiple' => true, 'options' => get_file_category_parent_options()])
                </div>
                @input(['name' => 'download_code', 'label' => __('manage::document.download_code'), 'helper' => "<a target='_blank' href='/admin/seo/ads'>Xem mã code tại đây</a>"])
                @checkbox(['name' => 'is_active', 'label' => __('manage::document.is_active'), 'default' => true])
                @mediamanager(['name' => 'file', 'label' => __('manage::document.file')])
                @mediamanager(['name' => 'image', 'label' => __('manage::document.image')])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="manage_file_Seo">
        @seo
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#doc_type').change(function() {
                const type = $(this).val();
                if (type == 'doc') {
                    $('.circulars').css('display', 'none');
                    $('.documentation').css('display', 'block');

                    $('.doc-category').css('display', 'block');
                    $('.form-category').css('display','none');
                } else {
                    $('.circulars').css('display', 'block');
                    $('.documentation').css('display','none');

                    $('.doc-category').css('display', 'none');
                    $('.form-category').css('display', 'block');
                }
            });
            $('#doc_type').trigger('change');
        })
    </script>
@endpush
