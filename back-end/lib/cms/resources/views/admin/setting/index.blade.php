@extends('core::admin.master')

@section('meta_title', __('cms::setting.index.page_title'))

@section('page_title', __('cms::setting.index.page_title'))

@section('page_subtitle', __('cms::setting.index.page_subtitle'))

@section('breadcrumb')
<nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
        <li class="breadcrumb-item">{{ trans('cms::setting.index.breadcrumb') }}</li>
    </ol>
</nav>
@stop

@section('content')
<form action="{{ route('setting.admin.setting.save') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('cms::setting.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="btn-group">
                        <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @input(['name' => 'item_on_page', 'label' => __('cms::setting.item_on_page'), 'default' => 20])
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @input(['name' => 'words_on_description', 'label' => __('cms::setting.words_on_description'), 'default' => 25])
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @input(['name' => 'text_content_list', 'label' => __('cms::setting.text_content_list'), 'default' => 'Nội dung bài viết'])
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_facebook', 'label' => '', 'placeholder' => __('cms::setting.is_display_facebook'), 'default' => true])
                </div>
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_instagram', 'label' => '', 'placeholder' => __('cms::setting.is_display_instagram'), 'default' => true])
                </div>
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_youtube', 'label' => '', 'placeholder' => __('cms::setting.is_display_youtube'), 'default' => true])
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_linkedin', 'label' => '', 'placeholder' => __('cms::setting.is_display_linkedin'), 'default' => true])
                </div>
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_pinterest', 'label' => '', 'placeholder' => __('cms::setting.is_display_pinterest'), 'default' => true])
                </div>
                <div class="col-md-4">
                    @checkbox(['name' => 'is_display_twitter', 'label' => '', 'placeholder' => __('cms::setting.is_display_twitter'), 'default' => true])
                </div>
            </div>
            <hr>
            <h6>Cài đặt nhúng short code hiển thị ngoài giao diện người dùng</h6><br/>
            <div class="row">
                <div class="col-md-12">
                    @input(['name' => 'time_waiting', 'label' => __('cms::setting.time_waiting'), 'default' => 60])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'name_button_confirm', 'label' => __('cms::setting.name_button_confirm'), 'default' => 'Xác Nhận'])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'name_button_get_code', 'label' => __('cms::setting.name_button_get_code'), 'default' => 'LẤY MÃ XÁC NHẬN'])
                </div>
            </div>
            <hr>
            <h6>Cài đặt text hiển thị ngoài giao diện người dùng</h6><br/>
            <div class="row">
                <div class="col-md-6">
                    @input(['name' => 'txt_request_call', 'label' => __('cms::setting.txt_request_call'), 'default' => 'ĐẾN VỚI CHÚNG TÔI ĐỂ NHẬN ĐƯỢC HỖ TRỢ CHU ĐÁO'])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_request_recall', 'label' => __('cms::setting.txt_request_recall')])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_request_recall_desc', 'label' => __('cms::setting.txt_request_recall_desc'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_support', 'label' => __('cms::setting.txt_support'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_download_document', 'label' => __('cms::setting.txt_download_document'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_why_choose', 'label' => __('cms::setting.txt_why_choose'), 'default' => 'TẠI SAO CHỌN CHÚNG TÔI'])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_team', 'label' => __('cms::setting.txt_team'), 'default' => 'ĐỘI NGŨ NHÂN VIÊN'])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_team_description', 'label' => __('cms::setting.txt_team_description')])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_client_talk', 'label' => __('cms::setting.txt_client_talk'), 'default' => 'KHÁCH HÀNG NÓI GÌ VỀ CHÚNG TÔI'])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_post_top', 'label' => __('cms::setting.txt_post_top'), 'default' => 'BÀI VIẾT NỖI BẬT'])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_subcribe', 'label' => __('cms::setting.txt_subcribe'), 'default' => 'Đăng ký email để nhận bài viết mới nhất từ chúng tôi'])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_footer_des', 'label' => __('cms::setting.txt_footer_des'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_header_des', 'label' => __('cms::setting.txt_header_des'),])
                </div>

                <div class="col-md-6">
                    @input(['name' => 'txt_service', 'label' => __('cms::setting.txt_service'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_service_des', 'label' => __('cms::setting.txt_service_des'),])
                </div>

                <div class="col-md-6">
                    @input(['name' => 'txt_category', 'label' => __('cms::setting.txt_category'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_category_des', 'label' => __('cms::setting.txt_category_des'),])
                </div>
                <hr/>

                <div class="col-md-6">
                    @input(['name' => 'txt_faq', 'label' => __('cms::setting.txt_faq'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_faq_des', 'label' => __('cms::setting.txt_faq_des'),])
                </div>
                <hr/>

                <div class="col-md-6">
                    @input(['name' => 'txt_brand', 'label' => __('cms::setting.txt_brand'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_brand_des', 'label' => __('cms::setting.txt_brand_des'),])
                </div>
                <hr/>

                <div class="col-md-6">
                    @input(['name' => 'txt_btn_partner', 'label' => __('cms::setting.txt_btn_partner'),])
                </div>

                <div class="col-md-6">
                    @input(['name' => 'txt_btn_partner_value', 'label' => __('cms::setting.txt_btn_partner_value'),])
                </div>
                <hr/>
            
                <div class="col-md-6">
                    @input(['name' => 'txt_portfolio', 'label' => __('cms::setting.txt_portfolio'),])
                </div>

                <div class="col-md-6">
                    @input(['name' => 'txt_client', 'label' => __('cms::setting.txt_client'),])
                </div>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_client_des', 'label' => __('cms::setting.txt_client_des'),])
                </div>
                <hr/>
                <div class="col-md-6">
                    @textarea(['name' => 'txt_recall_des', 'label' => __('cms::setting.txt_recall_des'),])
                </div>

                <div class="col-md-6">
                    @input(['name' => 'txt_header_top_name', 'label' => __('cms::setting.txt_header_top_name'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_header_top_value', 'label' => __('cms::setting.txt_header_top_value'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'btn_name_call_immediately', 'label' => __('cms::setting.btn_name_call_immediately'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'btn_name_register', 'label' => __('cms::setting.btn_name_register'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_footer_policy_name', 'label' => __('cms::setting.txt_footer_policy_name'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_footer_policy_value', 'label' => __('cms::setting.txt_footer_policy_value'),])
                </div>
                <hr/>
                <div class="col-md-6">
                    @input(['name' => 'btn_follow_on_google', 'label' => __('cms::setting.btn_follow_on_google'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'value_follow_on_google', 'label' => __('cms::setting.value_follow_on_google'),])
                </div>
                <div class="col-md-6">
                    @input(['name' => 'txt_name_rss_category', 'label' => __('cms::setting.txt_name_rss_category'),])
                </div>
                <div class="col-md-12">
                    @textarea(['name' => 'txt_search_file', 'label' => __('cms::setting.txt_search_file')])
                </div>
                <div class="col-md-12">
                    @editor(['name' => 'txt_content_rss', 'label' => __('cms::setting.txt_content_rss')])
                </div>
            </div>
            <h6>Cài đặt text hiển thị ở trang chủ</h6><br/>
            <div class="row">
                <div class="col-md-12">
                    @gallerymanager(['name' => 'faqs_gallery', 'media_type' => 'gallery', 'label' => __('cms::setting.faqs_gallery')])
                    @gallerymanager(['name' => 'contact_gallery', 'media_type' => 'gallery', 'label' => __('cms::setting.contact_gallery')])
                    @mediamanager(['name' => 'client_banner', 'label' => __('cms::setting.client_banner')])
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <div class="btn-group">
                <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
            </div>
        </div>
    </div>
</form>
@stop

@assetadd('seo.script', 'vendor/cms/assets/admin/js/setting.js', ['jquery'])
