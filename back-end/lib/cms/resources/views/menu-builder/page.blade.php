@sumoselect([
    'name' => 'menu_builder_args[id]',
    'label' => __('cms::menu-builder.page'),
    'options' => get_page_menu_builder_options(),
    'value' => $item->menu_builder_args['id'] ?? '',
    'search' => true,
    'allowClear' => true
])
