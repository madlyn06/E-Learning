@extends('core::admin.master')

@section('meta_title', __('Import internal links'))

@section('page_title', __('Import internal links'))

@section('page_subtitle', __('Import internal links'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seo.admin.internal-links.index') }}">{{ trans('seo::internal-link.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('Import') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('seo.admin.import-internal-links.import') }}" method="POST">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('Import internal links') }}
                        </h6>
                    </div>
                    <div class="text-right">
	                    <div class="btn-group">
	                        <button class="btn btn-success" type="submit">{{ __('Import') }}</button>
	                    </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs scrollable">
                    <li class="nav-item">
                        <a class="nav-link active save-tab" data-toggle="pill" href="#seo_internal-link_Info">
                            {{ __('Tab Info') }}
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="seo_internal-link_Info">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                @textarea([
                                    'name' => 'keywords_links',
                                    'label' => __('Nhập danh sách từ khoá và link'),
                                    'rows'=> 7,
                                    'helper' => '<i>Nhập từ khoá trước, link sau theo định dạng.<br/>
                                        keyword, link; <strong style="color: red">ví dụ:</strong> google master, https://google.com/webmaster; facebook, https://fb.com; <br/>
                                        Mỗi dòng sẽ cách nhau bằng dấu ;
                                    </i>'
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
            	<div class="btn-group">
	                <button class="btn btn-success" type="submit">{{ __('Import') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop
