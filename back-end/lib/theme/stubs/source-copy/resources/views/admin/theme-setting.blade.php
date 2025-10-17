<div class="form-horizontal">
    @input(['name' => 'demo_text', 'label' => __('Text')])
    @textarea(['name' => 'demo_textarea', 'label' => __('Textarea')])
    @checkbox(['name' => 'demo_checkbox', 'label' => __('Checkbox')])

    @select(['name' => 'demo_select', 'label' => __('Select'), 'options' => theme_demo_options()])
    @selecth(['name' => 'demo_selecth', 'label' => __('Select 2'), 'options' => theme_demo_options()])
    @sumoselect(['name' => 'demo_sumoselect', 'label' => __('Sumo Select'), 'options' => theme_demo_options()])
    @sumoselect(['name' => 'demo_select_multiple', 'label' => __('Multiple Select'), 'options' => theme_demo_options(), 'multiple' => true])

    @dateinput(['name' => 'demo_dateinput', 'label' => __('Date')])
    @datetimeinput(['name' => 'demo_datetimeinput', 'label' => __('Date Time')])
    @daterangeinput(['name' => 'demo_daterangeinput', 'label' => __('Date Range')])
    @datetimerangeinput(['name' => 'demo_datetimerangeinput', 'label' => __('Date Time Range')])

    @input(['name' => 'demo_money', 'label' => __('Money'), 'mask' => 'money'])

    <hr>

    @editor(['name' => 'demo_editor', 'label' => __('Editor')])

    <hr>

    @mediamanager(['name' => 'demo_mediamanager', 'label' => __('Media Manager')])
    @mediafile(['name' => 'demo_image', 'label' => __('Image')])
    @gallery(['name' => 'demo_gallery', 'label' => __('Gallery'), 'item' => gettype($item) == 'array' ? '' : $item])
</div>
