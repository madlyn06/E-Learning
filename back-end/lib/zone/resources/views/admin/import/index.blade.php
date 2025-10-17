@extends('core::admin.master')

@section('meta_title', __('zone::import.index.page_title'))

@section('page_title', __('zone::import.index.page_title'))

@section('page_subtitle', __('zone::import.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('zone.admin.province.index') }}">{{ trans('zone::province.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('zone::import.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('zone.admin.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('zone::import.index.page_title') }}
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
                <p>
                    <a href="https://www.gso.gov.vn/dmhc2015/" target="_blank">
                        <i class="fas fa-cloud-download-alt"></i>
                        {{ __('zone::import.download_file_excel') }}
                    </a>
                </p>
                <div>
                    <input type="file" name="file" id="file-1" class="custom-input-file" accept=".xlsx, .xls, .csv" />
                    <label for="file-1">
                        <i class="fa fa-upload"></i>
                        <span>Choose a file…</span>
                    </label>
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

@push('scripts')
    <script>
        jQuery(document).ready(function ($) {
            var $customInputFile = $('.custom-input-file');

            // Methods
            function change($input, $this, $e) {
                var fileName,
                    $label = $input.next('label'),
                    labelVal = $label.html();

                if ($this && $this.files.length > 1) {
                    fileName = ($this.getAttribute('data-multiple-caption') || '').replace('{count}', $this.files.length);
                } else if ($e.target.value) {
                    fileName = $e.target.value.split('\\').pop();
                }

                if (fileName) {
                    $label.find('span').html(fileName);
                } else {
                    $label.html(labelVal);
                }
            }

            function focus($input) {
                $input.addClass('has-focus');
            }

            function blur($input) {
                $input.removeClass('has-focus');
            }

            // Events
            if ($customInputFile.length) {
                $customInputFile.each(function () {
                    var $input = $(this);

                    $input.on('change', function (e) {
                        var $this = this,
                            $e = e;

                        change($input, $this, $e);
                    });

                    // Firefox bug fix
                    $input.on('focus', function () {
                        focus($input);
                    })
                        .on('blur', function () {
                            blur($input);
                        });
                });
            }
        });
    </script>
@endpush
