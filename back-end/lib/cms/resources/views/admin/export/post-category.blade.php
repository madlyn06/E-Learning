<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="cCategory">
  <label class="form-check-label" for="cCategory">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsCCategory as $key => $column)
  <div class="form-check">
    <input class="form-check-input c-category" type="checkbox" name="columns[{{ $key }}]" value="{{ $key }}" id="cmsCategory{{ $key }}">
    <label class="form-check-label" for="cmsCategory{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#cCategory').change(function() {
      if(this.checked) {
        $('input.c-category').prop('checked', true);
      } else {
        $('input.c-category').prop('checked', false);
      }
    });
  });
</script>
@endpush
