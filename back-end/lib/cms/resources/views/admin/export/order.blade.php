<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="eOrder">
  <label class="form-check-label" for="eOrder">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsOrder as $key => $column)
  <div class="form-check">
    <input class="form-check-input e-order" type="checkbox" name="columns[{{ $key }}]"  value="{{ $key }}" id="eOrder{{ $key }}">
    <label class="form-check-label" for="eOrder{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#eOrder').change(function() {
      if(this.checked) {
        $('input.e-order').prop('checked', true);
      } else {
        $('input.e-order').prop('checked', false);
      }
    });
  });
</script>
@endpush
