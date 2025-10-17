@sumoselect([
    'name' => 'menu_builder_args[id]',
    'label' => __('cms::menu-builder.category'),
    'options' => get_cms_category_menu_builder_options(),
    'value' => $item->menu_builder_args['id'] ?? '',
    'search' => true,
    'allowClear' => true
])
