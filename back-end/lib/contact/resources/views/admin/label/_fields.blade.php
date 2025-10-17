@input(['name' => 'name', 'label' => __('contact::label.name')])

<b>Type</b>
@foreach(get_contact_label_type_options() as $key => $type)
<div class="form-check">
    <input class="form-check-input" type="radio" name="type" id="{{ $key }}" value="{{ $key }}" style="margin-top: 10px" @if ($item && $item->type == $key) checked @endif>
    <label class="form-check-label" for="{{ $key }}">
        <button type="button" class="btn btn-{{ $key }}">{{ $type }}</button>
    </label>
</div>
@endforeach
