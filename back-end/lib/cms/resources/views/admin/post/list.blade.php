<div class="card-body table-responsive p-0">
  <table class="table table-centered table-striped table-bordered mb-0 toggle-circle">
    <thead>
      <tr>
        <th>
          <input type="checkbox" id="group" name="group2" onclick="checkItem('group', 'listContentItem');" />
        </th>
        <th>{{ __('STT') }}</th>
        <th>{{ __('cms::content-list.name') }}</th>
        <th>{{ __('cms::content-list.target') }}</th>
        <th nowrap>{{ __('cms::category.created_at') }}</th>
        <th nowrap>
          <a href="#" data-toggle="modal" data-target="#deleteItemListContent" class="btn btn-danger ml-2">
            Delete
          </a>
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $key => $item)
      <tr>
        <td>
          <input type="checkbox" class="listContentItem" value="{{$item->id}}">
        </td>
        <td>{{ $key + 1 }}</td>
        <td>
          {{ $item->parent_id ? '-- ' : '' }}{{ $item->name }}
        </td>
        <td>{{ $item->target }} </td>
        <td nowrap>{{ $item->created_at }}</td>
        <td nowrap class="text-right">
          @admincan('cms.admin.content-list.edit')
          <a href="{{ route('cms.admin.content-list.edit', $item->id) }}" class="btn btn-success-soft btn-sm mr-1">
            <i class="fas fa-pencil-alt"></i>
          </a>
          @endadmincan

          @admincan('cms.admin.content-list.destroy')
          <table-button-delete url-delete="{{ route('cms.admin.content-list.destroy', $item->id) }}"></table-button-delete>
          @endadmincan
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>


<input type="hidden" value="{{ route('cms.admin.comment.reply', '__ID__') }}" id="url">
<input type="hidden" value="{{ route('cms.admin.comment.publish') }}" id="url-publish">
<input type="hidden" id="comment_id">
<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Trả lời bình luận "<span id="title"></span>"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="comment_content" placeholder="Enter content" rows="5"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-submit-reply">Reply Now</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteItemListContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Are you sure delete the items?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" style="margin-left: 183px;">
        <a href="#" class="btn btn-success" onclick="deleteCheckedItem()">Yes</a>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
        <div>
        </div>
      </div>
    </div>
  </div>
</div>

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
    $('input:checkbox.listContentItem').each(function() {
      var sThisVal = (this.checked ? $(this).val() : "");
      if (sThisVal) {
        arrItemIds.push(sThisVal);
      }
    });
    if (arrItemIds.length > 0) {
      $.ajax({
        url: adminPath + '/cms/content-lists/delete-multiple',
        method: 'DELETE',
        data: {
          ids: arrItemIds
        },
        success: function(response) {
          location.reload();
        },
        error: function(e) {
          console.log(e)
        }
      });
    } else {
      swal('Warning', 'Vui lòng chọn ít nhất một item để xoá!', 'warning')
    }
  }
</script>

@endpush
