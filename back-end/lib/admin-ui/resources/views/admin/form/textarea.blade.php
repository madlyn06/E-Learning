<div class="form-group row component-{{ $name }}">
    <label for="{{ $name }}" class="col-12 col-form-label font-weight-600">{{ $label }} {!! isset($required) ? '<span style="color: red; margin-left: 0px">*</span>' : '' !!}</label>
    <div class="col-12">
        <textarea name="{{ $name }}"
                  id="{{ $name }}"
                  class="form-control {{ !empty($autoResize) ? 'autoResize' : '' }} @error(get_dot_array_form($name)) is-invalid @enderror"
                  placeholder="{{ $placeholder ?? $label }}"
                  rows="{{ $rows ?? 3 }}"
                  {{ !empty($disabled) ? 'disabled' : '' }}
                  {{ !empty($readonly) ? 'readonly' : '' }}
        >{{ old(get_dot_array_form($name), $value ?? object_get($item, get_dot_array_form($name))) }}</textarea>

        @error(get_dot_array_form($name))
            <span class="invalid-feedback text-left">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        @if(!empty($helper))
            <span class="helper-block">
                {!! $helper !!}
            </span>
        @endif
    </div>
</div>

@assetadd('autoResize', asset('vendor/newnet-admin/js/scripts/textarea.autoResize.js'), ['jquery'])
