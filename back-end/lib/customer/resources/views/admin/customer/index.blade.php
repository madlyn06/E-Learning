@extends('core::admin.master')

@section('meta_title', __('customer::customer.index.page_title'))

@section('page_title', __('customer::customer.index.page_title'))

@section('page_subtitle', __('customer::customer.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('customer::customer.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('customer::customer.index.page_title') }}
                    </h6>
                </div>
                <div class="text-right">
                    <div class="actions">
	                    @admincan('customer.admin.customer.create')
	                        <a href="{{ route('customer.admin.customer.create') }}" class="action-item">
	                            <i class="fa fa-plus"></i>
	                            {{ __('core::button.add') }}
	                        </a>
                            <a href="#" id="btnDeleteCustomer" data-toggle="modal" data-target="#deleteImage" class="btn btn-danger btn-sm mr-1" style="display: none">
                                {{ __('customer::customer.delete') }}
                            </a>
                        @endadmincan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                <thead>
                <tr>
                    <th>
                        <div class="checkbox checkbox-primary">
                            <input id="group" name="group2" onclick="checkCustomerItem('group', 'itemCustomer');" type="checkbox">
                            <label for="group"></label>
                        </div>
                    </th>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('customer::customer.name') }}</th>
                    <th>{{ __('customer::customer.phone') }}</th>
                    <th>{{ __('customer::customer.email') }}</th>
                    <th>{{ __('customer::customer.group') }}</th>
                    <th>{{ __('customer::customer.created_at') }}</th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox{{$item->id}}" type="checkbox" class="itemCustomer" value="{{$item->id}}" onclick="isDisplayDeleteButton()">
                                <label for="checkbox{{$item->id}}"></label>
                            </div>
                        </td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <a href="{{ route('customer.admin.customer.edit', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ object_get($item, 'group.name') }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td class="text-right">
                        	@admincan('customer.admin.customer.edit')
	                            <a href="{{ route('customer.admin.customer.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
	                                <i class="fas fa-pencil-alt"></i>
	                            </a>
                            @endadmincan

                            @admincan('customer.admin.customer.destroy')
                            	<table-button-delete url-delete="{{ route('customer.admin.customer.destroy', $item->id) }}"></table-button-delete>
                            @endadmincan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $items->appends(Request::all())->render() !!}
        </div>
    </div>
    <div class="modal fade" id="deleteImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure delete the items?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="margin-left: 183px;">
                    <a href="#" class="btn btn-success deleteImageListView" id="deleteImageListView" onclick="deleteCheckedCustomerItem()">Yes</a>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script>
        function isDisplayDeleteButton() {
            var baseCheck = $('.itemCustomer').is(":checked");
            $('.itemCustomer').each(function() {
                if (!$(this).is(':disabled')) {
                    if (baseCheck) {
                        $('#btnDeleteCustomer').css('display', 'inline');
                    } else {
                        $('#btnDeleteCustomer').css('display', 'none');
                    }
                }
            });
        }
        function checkCustomerItem(baseId, itemClass) {
            var baseCheck = $('#' + baseId).is(":checked");
            if (baseCheck) {
                $('#btnDeleteCustomer').css('display', 'inline');
            } else {
                $('#btnDeleteCustomer').css('display', 'none');
            }
            $('.' + itemClass).each(function() {
                if (!$(this).is(':disabled')) {
                    $(this).prop('checked', baseCheck);
                }
            });
        }

        function deleteCheckedCustomerItem() {
            let arrayCustomerIds = [];
            $('input:checkbox.itemCustomer').each(function () {
                var sThisVal = (this.checked ? $(this).val() : "");
                if (sThisVal) {
                    arrayCustomerIds.push(sThisVal);
                }
            });
            if (arrayCustomerIds.length > 0) {
                $.ajax({
                    url: adminPath + '/customer/customer/' + JSON.stringify(arrayCustomerIds),
                    method: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        location.reload();
                    },
                    error: function (e) {
                        console.log(e)
                    }
                });
            } else {
                alert('Please choose at least a item.')
            }
        }

    </script>
@endpush
