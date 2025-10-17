@extends('core::admin.master')

@section('meta_title', __('cms::sync.index.page_title'))

@section('page_title', __('cms::sync.index.page_title'))

@section('page_subtitle', __('cms::sync.index.page_subtitle'))

@section('breadcrumb')
<nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
  <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('cms::sync.index.breadcrumb') }}</li>
  </ol>
</nav>
@stop

@section('content')
<div class="card mb-4">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h6 class="fs-17 font-weight-600 mb-0">
          {{ __('cms::sync.index.page_title') }}
        </h6>
      </div>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('cms.admin.sync.save') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card mb-4">
        <div class="card-body">
          @if (!empty($trackingInfo))
          <div class="row mb-2">
            <div class="col-md-12">
              @if ($trackingInfo['status'] == 'successfully')
              <p>Dữ liệu đã được đồng bộ. Lần cập nhật gần nhất {{ $trackingInfo['updated_at'] }}</p>
              @else
              <p id="message-status">Dữ liệu đang được đồng bộ ...</p>
              @endif
              <div class="progress mb-4">
                <div id="progress-bar-handle" class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $trackingInfo['processed'] }}%">
                {{ $trackingInfo['processed'] }}%</div>
              </div>
            </div>
          </div>
          @endif
          <div class="row">
            <div class="col-md-12">
              @input(['name' => 'wordpress_url_api', 'label' => __('cms::sync.wordpress_url_api'),])
              @checkbox(['name' => 'change_internal_link', 'label' => '', 'placeholder' => __('Thay đổi internal link trong bài viết'),])
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <i style="color: red">{{ __('cms::sync.note')}}</i>
            </div>
          </div>


          <table style="margin-top: 20px" class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
            <thead>
            <tr>
                <th nowrap>{{ __('St.') }}</th>
                <th nowrap>{{ __('Trạng thái') }}</th>
                <th nowrap>{{ __('Message') }}</th>
                <th nowrap>{{ __('Processed') }}</th>
                <th nowrap>{{ __('cms::post.created_at') }}</th>
                <th nowrap></th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td nowrap>{{ $loop->index + $items->firstItem() }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->message }}</td>
                    <td>{{ $item->processed }}</td>
                    <td nowrap>{{ $item->created_at }}</td>
                    <td class="text-right" nowrap>
                      @admincan('cms.admin.post.destroy')
                        <table-button-delete url-delete="{{ route('cms.admin.sync.deleteSyncProcess', $item->id) }}"></table-button-delete>
                      @endadmincan
                    </td>
                </tr>
            @endforeach
            </tbody>
          </table>

        {!! $items->appends(Request::all())->render() !!}

        </div>
        <div class="card-footer text-right">
          <div class="btn-group">
            <button class="btn btn-success" type="submit">{{ __('cms::sync.btn_save') }}</button>
            <button class="btn btn-primary" type="submit" name="sync" value="1">{{ __('cms::sync.btn_sync') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@stop

@push('scripts')
<script>
  const getProgressHandleSyncData = function() {
    $.ajax({
      type: 'GET',
      url: `/admin/sync-tracking`,
      dataType: 'json',
      success: function(data) {
        const progress = data.processed;
        $('#progress-bar-handle').css('width', `${progress}%`);
        $('#progress-bar-handle').text(`${progress}%`);
        if (progress == 100) {
          console.log(`Finished progress: ${progress}%`);
          $('#message-status').text(data.message);
        } else {
          if (!data.status) {
            setTimeout(getProgressHandleSyncData, 2000)
          }
        }
      },
      error: function(err) {
        console.log(err);
      }
    });
  }
  getProgressHandleSyncData()
</script>
@endpush