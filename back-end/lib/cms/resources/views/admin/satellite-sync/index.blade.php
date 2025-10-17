@extends('core::admin.master')

@section('meta_title', __('Đồng bộ dữ liệu qua site vệ tinh'))

@section('page_title', __('Đồng bộ dữ liệu qua site vệ tinh'))

@section('page_subtitle', __('Đồng bộ dữ liệu qua site vệ tinh'))

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
          {{ __('Đồng bộ dữ liệu qua site vệ tinh') }}
        </h6>
      </div>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('cms.admin.satellite-sync.sync') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card mb-4">
        <div class="card-body">
          
          <div class="row">
            <div class="col-md-12">
              @sumoselect(['name' => 'satellite_site', 'label' => __('Chọn site vệ tinh'), 'options' => get_satellite_site_options(), 'multiple' => true])
            </div>
          </div>

          <table style="margin-top: 20px" class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="group" name="group2" onclick="checkItem('group', 'postItem');" />
                </th>
                <th nowrap>{{ __('Site') }}</th>
                <th nowrap>{{ __('Trạng thái') }}</th>
                <th nowrap>{{ __('Message') }}</th>
                <th nowrap>{{ __('cms::post.created_at') }}</th>
                <th nowrap></th>
            </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <th>
                        <input type="checkbox" id="group" name="group2" onclick="checkItem('group', 'postItem');" />
                    </th>
                    <td>{{ $item->sites_name }}</td>
                    <td>{{ $item->status }}</td>
                    <td nowrap>{{ $item->message }}</td>
                    <td nowrap>{{ $item->created_at->format('Y-m-d H:m:i') }}</td>
                    <td class="text-right" nowrap>
                        @admincan('cms.admin.satellite-sync.destroy')
                            <table-button-delete url-delete="{{ route('cms.admin.satellite-sync.destroy', $item->id) }}"></table-button-delete>
                        @endadmincan
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer text-right">
          <div class="btn-group">
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function checkItem(baseId, itemClass) {
        var baseCheck = $('#' + baseId).is(":checked");
        $('.' + itemClass).each(function() {
            if (!$(this).is(':disabled')) {
                $(this).prop('checked', baseCheck);
            }
        });
    }
    function deleteCheckedItem() {
        let arrItemIds = [];
        $('input:checkbox.postItem').each(function () {
            var sThisVal = (this.checked ? $(this).val() : "");
            if (sThisVal) {
                arrItemIds.push(sThisVal);
            }
        });
        if (arrItemIds.length > 0) {
            $.ajax({
                url: adminPath + '/cms/satellite-sync/delete-multiple',
                method: 'DELETE',
                data: {
                    ids: arrItemIds
                },
                success: function (response) {
                    location.reload();
                },
                error: function (e) {
                    console.log(e)
                }
            });
        } else {
            // alert('Please choose at least a item.')
            swal('Warning', 'Vui lòng chọn ít nhất một bài viết để xoá!', 'warning')
        }
    }
</script>

@endpush
