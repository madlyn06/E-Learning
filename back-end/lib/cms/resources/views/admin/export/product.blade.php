<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="eProduct">
  <label class="form-check-label" for="eProduct">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsProduct as $key => $column)
  <div class="form-check">
    <input class="form-check-input e-product" type="checkbox" name="columns[{{ $key }}]" id="product{{ $key }}"  value="{{ $key }}">
    <label class="form-check-label" for="product{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#eProduct').change(function() {
      if(this.checked) {
        $('input.e-product').prop('checked', true);
      } else {
        $('input.e-product').prop('checked', false);
      }
    });
  });
</script>
@endpush
