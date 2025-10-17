@extends('core::admin.master')

@section('meta_title', __('Chi tiết về các trang được cào'))

@section('page_title', __('Chi tiết về các trang được cào'))

@section('page_subtitle', __('Chi tiết về các trang được cào'))

@section('breadcrumb')
  <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
    <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
      <li class="breadcrumb-item active"><a href="{{ route('cms.admin.crawl-history.index') }}">{{ trans('History') }}</a></li>
      <li class="breadcrumb-item active">{{ trans('Show') }}</li>
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
                    <th>ID</th>
                    <th>{{ __('URL') }}</th>
                    <th>{{ __('Tiêu đề gốc') }}</th>
                    <th>{{ __('Tiêu đề rewrite') }}</th>
                    <th>{{ __('Trạng thái') }}</th>
                    <th nowrap>{{ __('Error') }}</th>
                    <th nowrap>{{ __('Ngày cào') }}</th>
                    <th nowrap></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->url }}</td>
                        <td>{{ $item->origin_title }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->status }}
                          <p style="color: red; font-weight: bold">{{ \Newnet\Cms\Enums\CrawlHistoryItemEnum::getLabel($item->status) }}</p>
                          @if ($item->crawlHistory->schedule_at)
                            <p>(Ngày đăng: {{ $item->crawlHistory->schedule_at->format('Y-m-d H:m:i') }})</p>
                          @endif
                          @if ($item->published_at)
                            <p>(Ngày đăng: {{ $item->published_at->format('Y-m-d H:m:i') }})</p>
                          @endif
                        </td>
                        <td nowrap>
                            @if($item->status == 'FAILED')
                            <a href="{{ route('cms.admin.crawl-history-item.error', $item->id) }}">{{ 'Xem lỗi' }}</a>
                            @endif
                        </td>
                        <td nowrap>{{ $item->created_at }}</td>
                        <td nowrap class="text-right">
                            @admincan('cms.admin.crawl-history.edit')
                              @if (!$item->handled_file_name && $item->status == 'FAILED')
                                <a href="{{ route('cms.admin.crawl-history-item.reRewrite', $item->id) }}" class="btn btn-success-soft btn-sm mr-1 retranslate-item">
                                  <i class="fas fa-sync-alt"></i>
                                  Re-Rewrite
                                </a>
                              @endif
                            @endadmincan
                            @admincan('cms.admin.crawl-history.destroy')
                              <table-button-delete url-delete="{{ route('cms.admin.crawl-history.destroy', $item->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@push('scripts')
  <script>
    $(document).ready(function() {
      checkCrawlStatus();

      $('.retranslate-item').click(function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        $.ajax({
          url: url,
          method: 'GET',
          success: function(response) {
            if (response.status == 'FAILED') {
              alert('Dịch lại thất bại. ' + response.message);
            } else {
              alert('Dịch lại thành công.');
              window.location.reload();
            }
          },
          error: function(error) {
            alert('Dịch lại thất bại.');
          }
        });
      });
    });

    function checkCrawlStatus() {
      const crawlHistory = @json($crawlHistory);
      if (crawlHistory) {
        const status = crawlHistory.status;
        console.log('Checking status: ' + status);
        if (status == 'FAILED' || status == 'COMPLETED') {
          return;
        } else {
          setTimeout(() => {
            window.location.reload();
          }, 10000);
        }
      }
    }
  </script>
@endpush
