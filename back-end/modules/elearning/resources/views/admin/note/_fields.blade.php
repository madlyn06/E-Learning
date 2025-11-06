<div class="row">
    <div class="col-12 col-md-6">
        @select(['name' => 'user_id', 'label' => __('elearning::note.user'), 'options' => $users ?? [], 'value' => $item->user_id ?? null, 'required' => true])
        
        @select(['name' => 'lesson_id', 'label' => __('elearning::note.lesson'), 'options' => $lessons ?? [], 'value' => $item->lesson_id ?? null, 'required' => true])
        
        @input(['name' => 'time_iso', 'label' => __('elearning::note.time_iso'), 'value' => $item->time_iso ?? '00:00:00', 'required' => true])
        
        @input(['name' => 'time_seconds', 'label' => __('elearning::note.time_seconds'), 'value' => $item->time_seconds ?? 0, 'type' => 'number', 'required' => true])
    </div>
    
    <div class="col-12 col-md-6">
        @textarea(['name' => 'content', 'label' => __('elearning::note.content'), 'value' => $item->content ?? null, 'rows' => 10, 'required' => true])
    </div>
</div>
