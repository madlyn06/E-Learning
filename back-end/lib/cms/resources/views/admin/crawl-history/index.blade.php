@extends('core::admin.master')

@section('meta_title', __('Lịch sử cào'))

@section('page_title', __('Lịch sử cào'))

@section('page_subtitle', __('Lịch sử cào'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('Index') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('Thông tin các trang đã cào') }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form-inline newnet-table-search">
                <x-search-input/>
                <button type="submit" class="btn btn-primary mr-1">
                    {{ __('core::button.search') }}
                </button>
                <a href="{{ route('cms.admin.crawl-history.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('URLs') }}</th>
                    <th>{{ __('Prompt') }}</th>
                    <th>{{ __('CSS Selector') }}</th>
                    <th nowrap>{{ __('Bài viết') }}</th>
                    <th>{{ __('Action') }}</th>
                    <th nowrap>{{ __('Từ thay thế') }}</th>
                    <th nowrap>{{ __('Trạng thái') }}</th>
                    <th nowrap>{{ __('Ngày đăng') }}</th>
                    <th nowrap>{{ __('Ngày tạo') }}</th>
                    <th nowrap></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->index + $items->firstItem() }}</td>
                        <td>
                            <a href="{{ route('cms.admin.crawl-history-item.index', $item->id) }}">
                                {{ $item->urls }}
                            </a>
                        </td>
                        <td>{{ $item->prompt }}</td>
                        <td>{{ $item->css_selectors }}</td>
                        <td>{{ $item->crawlHistoryItems->count() }}</td>
                        <td>{{ \Newnet\Cms\Enums\PostActionEnum::getLabel($item->post_action) }}</td>
                        <td>{{ $item->replace_words_before }}</td>
                        <td>{{ \Newnet\Cms\Enums\CrawlHistoryEnum::getLabel($item->status) }}</td>
                        <td nowrap>{{ $item->schedule_at ? $item->schedule_at->format('Y-m-d H:m:i') : null }}</td>
                        <td nowrap>{{ $item->created_at->format('Y-m-d H:m:i') }}</td>
                        <td nowrap class="text-right">
                            @admincan('cms.admin.crawl-history.edit')
                                <a href="{{ route('cms.admin.crawl-history-item.index', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endadmincan
                            @admincan('cms.admin.crawl-history.destroy')
                                <table-button-delete url-delete="{{ route('cms.admin.crawl-history.destroy', $item->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $items->appends(Request::all())->render() !!}
        </div>
    </div>
@stop
