@extends('core::admin.master')

@section('meta_title', __('Export'))

@section('page_title', __('Export'))

@section('page_subtitle', __('Export data from your site'))

@section('breadcrumb')
  <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a>
      </li>
      <li class="breadcrumb-item active">{{ trans('Export') }}</li>
    </ol>
  </nav>
@stop

@section('content')
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="fs-17 font-weight-600 mb-0">
            {{ __('Export') }}
          </h6>
        </div>
      </div>
    </div>
    <div class="card-body">
      <h6><strong>Chọn loại data cần export</strong></h6>
      <form action="{{ route('cms.admin.export') }}" method="POST">
        @csrf
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="post1" value="post" checked>
          <label class="form-check-label" for="post1">
            Bài viết
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="postCategory2" value="post-category">
          <label class="form-check-label" for="postCategory2">
            Danh mục bài viết
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="product2" value="product">
          <label class="form-check-label" for="product2">
            Sản phẩm
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="productCategory2" value="product-category">
          <label class="form-check-label" for="productCategory2">
            Danh mục sản phẩm
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="download2" value="download">
          <label class="form-check-label" for="download2">
            Download
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="order2" value="order">
          <label class="form-check-label" for="order2">
            Đơn hàng
          </label>
        </div>
        {{-- <div class="form-check">
          <input class="form-check-input" type="radio" name="data_type" id="all1" value="all">
          <label class="form-check-label" for="all1">
            Tất cả
          </label>
        </div> --}}
        <h6 class="mt-3"><strong>Chọn các trường cần export</strong></h6>
        <div id="post-columns">
          @include('cms::admin.export.post')
        </div>
        <div id="post-category-columns">
          @include('cms::admin.export.post-category')
        </div>
        <div id="product-columns">
          @include('cms::admin.export.product')
        </div>
        <div id="product-category-columns">
          @include('cms::admin.export.product-category')
        </div>
        <div id="download-columns">
          @include('cms::admin.export.download')
        </div>
        <div id="order-columns">
          @include('cms::admin.export.order')
        </div>

        <div class="mt-3">
          @select(['name' => 'file_type', 'label' => 'Chọn loại file', 'options' => [
            ['value' => 'xlsx', 'label' => 'XLSX'],
            // ['value' => 'csv', 'label' => 'CSV'],
            // ['value' => 'txt', 'label' => 'Text'],
          ], 'selected' => 'xlsx', 'allowClear' => false])
        </div>

        <div class="mt-3">
          <button class="btn btn-primary" id="export" type="submit">Export</button>
        </div>
      </form>
    </div>
  </div>
@stop

@push('scripts')
  <script>
    $(document).ready(function() {
      $('#post-columns').show();
      $('#post-category-columns').hide();
      $('#product-columns').hide();
      $('#product-category-columns').hide();
      $('#download-columns').hide();
      $('#order-columns').hide();

      $('input[type=radio][name=data_type]').change(function() {
        var value = $(this).val();
        $('#post-columns').hide();
        $('#post-category-columns').hide();
        $('#product-columns').hide();
        $('#product-category-columns').hide();
        $('#download-columns').hide();
        $('#order-columns').hide();
        $('#' + value + '-columns').show();

        // Remove all selected columns
        $('input[type=checkbox]').prop('checked', false);
      });
    });
  </script>
@endpush
