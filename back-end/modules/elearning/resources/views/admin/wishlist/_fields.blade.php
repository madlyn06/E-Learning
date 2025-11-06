@input(['name' => 'id', 'type' => 'hidden'])

<div class="row">
    <div class="col-md-6">
        @select([
            'name' => 'user_id',
            'label' => __('elearning::wishlist.user'),
            'options' => $users ?? [],
            'value' => $item->user_id ?? old('user_id'),
            'required' => true
        ])
    </div>

    <div class="col-md-6">
        @select([
            'name' => 'course_id',
            'label' => __('elearning::wishlist.course'),
            'options' => $courses ?? [],
            'value' => $item->course_id ?? old('course_id'),
            'required' => true
        ])
    </div>
</div>

@if(isset($item) && $item->id)
<div class="row">
    <div class="col-md-6">
        @input([
            'name' => 'created_at',
            'type' => 'datetime-local',
            'label' => __('elearning::wishlist.created_at'),
            'value' => isset($item->created_at) ? $item->created_at->format('Y-m-d\TH:i') : old('created_at'),
            'readonly' => true,
            'disabled' => true
        ])
    </div>
    <div class="col-md-6">
        @input([
            'name' => 'updated_at',
            'type' => 'datetime-local',
            'label' => __('elearning::wishlist.updated_at'),
            'value' => isset($item->updated_at) ? $item->updated_at->format('Y-m-d\TH:i') : old('updated_at'),
            'readonly' => true,
            'disabled' => true
        ])
    </div>
</div>
@endif
