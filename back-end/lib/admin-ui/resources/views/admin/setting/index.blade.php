@extends('admin::master')

@section('meta_title', __('admin::setting.index.page_title'))

@section('page_title', __('admin::setting.index.page_title'))

@section('page_subtitle', __('admin::setting.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('admin::setting.index.breadcrumb') }}</li>
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
                            {{ __('admin::setting.index.page_title') }}
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
                <div class="form-horizontal">
                    @input(['name' => 'site_title', 'label' => __('admin::setting.general.site_title')])
                    @input(['name' => 'site_title_short', 'label' => __('admin::setting.general.site_title_short')])
                    @textarea(['name' => 'site_description', 'label' => __('admin::setting.general.site_description')])
                    @input(['name' => 'site_contact_phone', 'label' => __('admin::setting.general.site_contact_phone')])
                    @input(['name' => 'site_contact_phone_call', 'label' => __('admin::setting.general.site_contact_phone_call')])
                    @input(['name' => 'site_contact_phone_zalo', 'label' => __('admin::setting.general.site_contact_phone_zalo')])
                    @input(['name' => 'site_contact_email', 'label' => __('admin::setting.general.site_contact_email')])
                    @input(['name' => 'site_contact_address', 'label' => __('admin::setting.general.site_contact_address')])
                    @input(['name' => 'site_tax_no', 'label' => __('admin::setting.general.site_tax_no')])
                    @dateinput(['name' => 'site_date_founding', 'label' => __('admin::setting.general.site_date_founding'),])

                    @textarea([
                        'name' => 'map_iframe',
                        'label' => __('admin::setting.general.map_iframe'),
                        'helper' => __('admin::setting.general.helper_map_iframe')
                    ])
                    @input(['name' => 'captcha_key', 'label' => __('admin::setting.general.captcha_key')])
                    @input(['name' => 'working_time', 'label' => __('admin::setting.general.working_time')])

                    @mediafile(['name' => 'logo', 'label' => __('admin::setting.general.logo'), 'conversion' => '', 'clearable' => true])
                    @mediafile(['name' => 'logo_dark', 'label' => __('admin::setting.general.logo_dark'), 'conversion' => '', 'clearable' => true])
                    @mediafile(['name' => 'logo_light', 'label' => __('admin::setting.general.logo_light'), 'conversion' => '', 'clearable' => true])
                    @mediafile(['name' => 'logo_login', 'label' => __('admin::setting.general.logo_login'), 'conversion' => '', 'clearable' => true])
                    @mediafile(['name' => 'logo_admin', 'label' => __('admin::setting.general.logo_admin'), 'conversion' => '', 'clearable' => true])
                    @mediafile(['name' => 'favicon', 'label' => __('admin::setting.general.favicon'), 'conversion' => ''])
                    @mediafile(['name' => 'loading_image', 'label' => __('admin::setting.general.loading_image'), 'conversion' => '', 'clearable' => true])
                    @input(['name' => 'setting_facebook', 'label' => __('acl::user.facebook')])
                    @input(['name' => 'setting_instagram', 'label' => __('acl::user.instagram')])
                    @input(['name' => 'setting_youtube', 'label' => __('acl::user.youtube')])
                    @input(['name' => 'setting_pinterest', 'label' => __('acl::user.pinterest')])
                    @input(['name' => 'setting_linkedin', 'label' => __('acl::user.linkedin')])
                    @input(['name' => 'setting_twitter', 'label' => __('acl::user.twitter')])
                    @textarea([
                        'name' => 'script_header',
                        'label' => __('admin::setting.general.script_header'),
                        'helper' => __('admin::setting.general.helper_header')
                    ])
                    @textarea([
                        'name' => 'script_body',
                        'label' => __('admin::setting.general.script_body'),
                        'helper' => __('admin::setting.general.helper_body')
                    ])
                    @textarea([
                        'name' => 'script_footer',
                        'label' => __('admin::setting.general.script_footer'),
                        'helper' => __('admin::setting.general.helper_footer')
                    ])
                    @textarea([
                        'name' => 'robots_configuration',
                        'label' => __('admin::setting.general.robots_configuration'),
                    ])
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
