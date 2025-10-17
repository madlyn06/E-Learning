<div class="form-check">
  <input class="form-check-input" type="checkbox" name="select-all" id="cPost">
  <label class="form-check-label" for="cPost">
    <strong>Chọn tất cả</strong>
  </label>
</div>
@foreach ($columnsPost as $key => $column)
  <div class="form-check">
    <input class="form-check-input c-post" type="checkbox" name="columns[{{ $key }}]" id="cmsPost{{ $key }}">
    <label class="form-check-label" for="cmsPost{{ $key }}">
      {{ $column }}
    </label>
  </div>
@endforeach

@push('scripts')
<script>
  $(document).ready(function() {
    $('#cPost').change(function() {
      if(this.checked) {
        $('input.c-post').prop('checked', true);
      } else {
        $('input.c-post').prop('checked', false);
      }
    });
  });
</script>
@endpush
