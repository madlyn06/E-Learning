<div class="row">
    <div class="col-12 col-md-6">
        @input(['name' => 'name', 'label' => __('contact::contact.name')])
        @input(['name' => 'email', 'label' => __('contact::contact.email')])
        @input(['name' => 'phone', 'label' => __('contact::contact.phone')])
        @textarea(['name' => 'content', 'label' => __('contact::contact.content')])
        @checkbox(['name' => 'is_handle', 'label' => __('contact::contact.is_handle')])
    </div>
    <div class="col-12 col-md-6">
        @select(['name' => 'label_id', 'label' => __('contact::contact.label'), 'options' => get_contact_label_options()])
        @textarea(['name' => 'note', 'label' => __('contact::contact.note'), 'rows' => 12])
    </div>
</div>
