<div class="card-body table-responsive p-0">
  <table class="table table-centered table-striped table-bordered mb-0 toggle-circle">
    <thead>
      <tr>
        <th>{{ __('STT') }}</th>
        <th>{{ __('Name') }}</th>
        <th>{{ __('Content') }}</th>
        <th nowrap>{{ __('Is Publish') }}</th>
        <th nowrap>{{ __('cms::category.created_at') }}</th>
        <th nowrap></th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $key => $item)
      @php($key ++)
      <tr>
        <td>{{ $key }}</td>
        <td>
          {{ $item->name }} <br> {{ $item->email }} <br> {{ $item->phone }}
        </td>
        <td style="width: 45%;">
            {{ trim(str_pad('', $item->depth * 3, '-')) }}
            {{ $item->content }}
        </td>
        <td nowrap>
          @if($item->is_published)
          <span style="cursor: pointer;" class="badge badge-success publish-comment" data-is-publish="0" data-comment-id="{{ $item->id }}">Published</span>
          @else
          <span style="cursor: pointer;" class="badge badge-danger publish-comment" data-is-publish="1" data-comment-id="{{ $item->id }}">UnPublished</span>
          @endif
        </td>

        <td nowrap>{{ $item->created_at }}</td>
        <td nowrap class="text-right">
          @admincan('cms.admin.comment.create')
          <a data-toggle="modal" data-value="{{ json_encode($item) }}" data-target="#replyModal" title="Reply" class="btn btn-success-soft btn-sm mr-1 btn-reply">
            <i class="fa fa-reply"></i>
          </a>
          @endadmincan

          @admincan('cms.admin.comment.destroy')
          <table-button-delete url-delete="{{ route('cms.admin.comment.destroy', $item->id) }}"></table-button-delete>
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
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="replyModalLabel">Trả lời bình luận "<span id="titleComment"></span>"</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="comment_content_reply" placeholder="Enter content" rows="5"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-submit-reply">Reply Now</button>
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
  $(document).ready(function() {
    $('.btn-reply').click(function(e) {
      e.preventDefault();
      const data = $(this).data('value');
      $('#comment_id').val(data.id);
      $('#titleComment').text(data.content);
    });

    $('.btn-submit-reply').click(function() {
      const data = {
        parent_id: $('#comment_id').val(),
        post_id: '{{ $post->id }}',
        content: $('#comment_content_reply').val(),
      };
      let url = $('#url').val();
      url = url.replace('__ID__', $('#comment_id').val());
      $.ajax({
        url,
        method: 'POST',
        data,
        success: function(response) {
          swal('Replied comment successfully')
          setTimeout(() => {
            window.location.reload();
          }, 3000)
        },
        error: function(err) {
          console.log({ err });
        }
      })
    })

    // Publish a new comment
    $('.publish-comment').click(function() {
      const urlPublish = $('#url-publish').val();
      const isPublish = $(this).data('is-publish');
      const commentId = $(this).data('comment-id');
      $.ajax({
        url: urlPublish,
        method: 'POST',
        data: {
          comment_id: commentId,
          is_published: isPublish
        },
        success: function(response) {
          swal('Updated comment status successfully')
          setTimeout(() => {
            window.location.reload();
          }, 3000)
        },
        error: function(err) {
          console.log({err});
        }
      })
    })
  })
</script>
@endpush
