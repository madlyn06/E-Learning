@input(['name' => 'id', 'type' => 'hidden'])

<div class="row">
    <div class="col-md-6">
        @select([
            'name' => 'user_id',
            'label' => __('elearning::review.user'),
            'options' => $users ?? [],
            'value' => $item->user_id ?? old('user_id'),
            'required' => true
        ])

        @select([
            'name' => 'course_id',
            'label' => __('elearning::review.course'),
            'options' => $courses ?? [],
            'value' => $item->course_id ?? old('course_id'),
            'required' => true
        ])

        @select([
            'name' => 'rating',
            'label' => __('elearning::review.rating'),
            'options' => [
                '1' => '1 ' . __('elearning::review.star'),
                '2' => '2 ' . __('elearning::review.stars'),
                '3' => '3 ' . __('elearning::review.stars'),
                '4' => '4 ' . __('elearning::review.stars'),
                '5' => '5 ' . __('elearning::review.stars')
            ],
            'value' => $item->rating ?? old('rating'),
            'required' => true
        ])
    </div>

    <div class="col-md-6">
        @input([
            'name' => 'title',
            'label' => __('elearning::review.title'),
            'value' => $item->title ?? old('title')
        ])

        @select([
            'name' => 'status',
            'label' => __('elearning::review.status'),
            'options' => [
                'published' => __('elearning::review.statuses.published'),
                'pending' => __('elearning::review.statuses.pending'),
                'rejected' => __('elearning::review.statuses.rejected')
            ],
            'value' => $item->status ?? old('status', 'pending'),
            'required' => true
        ])
    </div>
</div>

<div class="row">
    <div class="col-12">
        @textarea([
            'name' => 'content',
            'label' => __('elearning::review.content'),
            'value' => $item->content ?? old('content'),
            'rows' => 5,
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
            'label' => __('elearning::review.created_at'),
            'value' => isset($item->created_at) ? $item->created_at->format('Y-m-d\TH:i') : old('created_at'),
            'readonly' => true,
            'disabled' => true
        ])
    </div>
    <div class="col-md-6">
        @input([
            'name' => 'updated_at',
            'type' => 'datetime-local',
            'label' => __('elearning::review.updated_at'),
            'value' => isset($item->updated_at) ? $item->updated_at->format('Y-m-d\TH:i') : old('updated_at'),
            'readonly' => true,
            'disabled' => true
        ])
    </div>
</div>
@endif
