<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="mDownload">
  <label class="form-check-label" for="mDownload">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsDownload as $key => $column)
  <div class="form-check">
    <input class="form-check-input m-download" type="checkbox" name="columns[{{ $key }}]"  value="{{ $key }}" id="mDownload{{ $key }}">
    <label class="form-check-label" for="mDownload{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#mDownload').change(function() {
      if(this.checked) {
        $('input.m-download').prop('checked', true);
      } else {
        $('input.m-download').prop('checked', false);
      }
    });
  });
</script>
@endpush
