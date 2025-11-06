<div class="row">
    <div class="col-12 col-md-6">
        @select(['name' => 'user_id', 'label' => __('elearning::comment.user'), 'options' => $users ?? [], 'value' => $item->user_id ?? null, 'required' => true])
        
        @select(['name' => 'lesson_id', 'label' => __('elearning::comment.lesson'), 'options' => $lessons ?? [], 'value' => $item->lesson_id ?? null, 'required' => true])
        
        @select(['name' => 'parent_id', 'label' => __('elearning::comment.parent'), 'options' => $parentComments ?? [], 'value' => $item->parent_id ?? null])
        
        @checkbox(['name' => 'is_spam', 'label' => __('elearning::comment.is_spam'), 'checked' => $item->is_spam ?? false])
    </div>
    
    <div class="col-12 col-md-6">
        @textarea(['name' => 'content', 'label' => __('elearning::comment.content'), 'value' => $item->content ?? null, 'rows' => 10, 'required' => true])
    </div>
</div>
