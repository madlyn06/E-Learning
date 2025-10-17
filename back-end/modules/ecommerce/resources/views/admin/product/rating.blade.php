<div class="card-body table-responsive p-0">
  <table class="table table-centered table-striped table-bordered mb-0 toggle-circle">
    <thead>
      <tr>
        <th>{{ __('STT') }}</th>
        <th>{{ __('Name') }}<br/>{{ __('Email / Phone') }}</th>
        <th>{{ __('Content') }}</th>
        <th>{{ __('Star') }}</th>
        <th>{{ __('Is Publish') }}</th>
        <th>{{ __('cms::category.created_at') }}</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
      <tr>
        <td>{{ $item->id }}</td>
        <td>
          {{ $item->name }} <br/>
          {{ $item->email }}
        </td>
        <td style="width: 45%;">
            {{ $item->comment }}
        </td>
        <td>{{ $item->stars }}</td>
        <td>
          @if($item->is_published)
          <span style="cursor: pointer;" class="badge badge-success publish-rating" data-is-publish="0" data-rating-id="{{ $item->id }}">Published</span>
          @else
          <span style="cursor: pointer;" class="badge badge-danger publish-rating" data-is-publish="1" data-rating-id="{{ $item->id }}">UnPublished</span>
          @endif
        </td>

        <td>{{ $item->created_at }}</td>
        <td class="text-right">
          @admincan('cms.admin.ratings.destroy')
          <table-button-delete url-delete="{{ route('cms.admin.ratings.destroy', $item->id) }}"></table-button-delete>
          @endadmincan
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<input type="hidden" value="{{ route('ecommerce.admin.ratings.publish') }}" id="url-publish-rating">

@push('scripts')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function() {
    // Publish a new rating
    $('.publish-rating').click(function() {
      const urlPublish = $('#url-publish-rating').val();
      const isPublish = $(this).data('is-publish');
      const ratingId = $(this).data('rating-id');
      $.ajax({
        url: urlPublish,
        method: 'POST',
        data: {
          rating_id: ratingId,
          is_published: isPublish
        },
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
