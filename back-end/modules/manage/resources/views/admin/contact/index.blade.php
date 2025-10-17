@extends('core::admin.master')

@section('meta_title', __('manage::contact.index.page_title'))

@section('page_title', __('manage::contact.index.page_title'))

@section('page_subtitle', __('manage::contact.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('manage::contact.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">
                        {{ __('manage::contact.index.page_title') }}
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
                <a href="{{ route('manage.admin.contact.index') }}" class="btn btn-danger">
                    {{ __('core::button.cancel') }}
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                    <tr>
                        <th>{{ __('#') }}</th>
                        <th>{{ __('manage::contact.name') }}</th>
                        <th>{{ __('manage::contact.content') }}</th>
                        <th>{{ __('manage::contact.status')}}</th>
                        <th>{{ __('manage::contact.created_at') }}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $loop->index + $items->firstItem() }}</td>
                            <td>
                                <a href="{{ route('manage.admin.contact.edit', $item->id) }}">
                                {{ $item->name }} <br> {{ $item->email }} <br> {{ $item->phone }}
                                </a>
                            </td>
                            <td>{{ $item->content }}</td>
                            <td>{{ Illuminate\Support\Str::upper($item->status) }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-right">
                                @admincan('manage.admin.contact.edit')
                                   
                                    <a data-toggle="modal" href="#" data-value="{{ json_encode($item) }}" data-target="#exampleModal" title="Reply" class="btn btn-success-soft btn-sm mr-1 btn-reply" style="background-color: rgb(211 250 255); color: #0fac04; width: 32px;border-color: rgb(167 255 247); border: 1px solid">
                                        <i class="fa fa-reply" style="font-size: 15px; margin-left: -5px; margin-top: 2px"></i>
                                    </a>
                                @endadmincan

                                @admincan('manage.admin.contact.destroy')
                                    <table-button-delete url-delete="{{ route('manage.admin.contact.destroy', $item->id) }}"></table-button-delete>
                                @endadmincan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {!! $items->appends(Request::all())->render() !!}
        </div>
    </div>


<input type="hidden" value="{{ route('admin.contact.post-reply', '__ID__') }}" id="url">
<input type="hidden" id="id">
<input type="hidden" id="email">
<input type="hidden" id="name">
<input type="hidden" id="phone">
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reply "<span id="title"></span>"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="title">Subject</label>
          <input type="text" class="form-control" id="subject" placeholder="Enter title">
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="content" placeholder="Enter content" rows="5"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-submit-reply">Reply Now</button>
      </div>
    </div>
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
  $(document).ready(function() {
    $('.btn-reply').click(function(e) {
      e.preventDefault();
      const data = $(this).data('value');
      $('#id').val(data.id);
      $('#name').val(data.name);
      $('#phone').val(data.phone);
      $('#email').val(data.email);
      $('#subject').val(data.subject);
      $('#title').text(data.subject);
    });

    $('.btn-submit-reply').click(function() {
      const idContact = $('#id').val();
      const data = {
        id: idContact,
        email: $('#email').val(),
        name: $('#name').val(),
        phone: $('#phone').val(),
        subject: $('#subject').val(),
        content: $('#content').val(),
      };
      let url = $('#url').val();
      url = url.replace('__ID__', idContact);
      $.ajax({
        url,
        method: 'POST',
        data,
        success: function(response) {
          window.location.reload();
        },
        error: function(err) {
          console.log({err});
        }
      })
    })
  })
</script>
@endpush
