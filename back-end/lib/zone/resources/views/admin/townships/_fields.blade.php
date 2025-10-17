<div class="form-group">
    <label for="name" class="font-weight-600">{{ __('zone::province.name') }}</label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           name="name"
           id="name"
           value="{{ old('name', object_get($item, 'name')) }}"
           placeholder="{{ __('zone::province.name') }}">
    @error('name')
        <span class="invalid-feedback text-left">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
    @input(['name' => 'code', 'label' => __('zone::province.code')])
    @checkbox(['name' => 'status', 'label' => '', 'placeholder' => __('zone::province.status')])
</div>
