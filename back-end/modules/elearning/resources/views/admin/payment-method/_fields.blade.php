@input(['name' => 'name', 'label' => __('elearning::payment_method.name'), 'value' => $item->name ?? '', 'required' => true])
@slug(['name' => 'code', 'label' => __('elearning::payment_method.code'), 'slugFrom' => '#name', 'required' => true, 'disabled' => $item && $item->id ? true : false])

@textarea(['name' => 'description', 'label' => __('elearning::payment_method.description'), 'value' => $item->description ?? ''])
@checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('elearning::payment_method.is_active'), 'value' => $item->is_active ?? ''])
@mediafile(['name' => 'logo', 'label' => __('elearning::payment_method.logo'), 'value' => $item->logo ?? ''])
@input(['name' => 'display_order', 'label' => __('elearning::payment_method.display_order'), 'value' => $item->display_order ?? ''])