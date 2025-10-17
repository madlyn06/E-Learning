<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="productCategory">
  <label class="form-check-label" for="productCategory">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsECategory as $key => $column)
  <div class="form-check">
    <input class="form-check-input e-category" type="checkbox" name="columns[{{ $key }}]" id="productCategory{{ $key }}"  value="{{ $key }}">
    <label class="form-check-label" for="productCategory{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#productCategory').change(function() {
      if(this.checked) {
        $('input.e-category').prop('checked', true);
      } else {
        $('input.e-category').prop('checked', false);
      }
    });
  });
</script>
@endpush
