@input(['name' => 'from_path', 'label' => __('seo::error-redirect.from_path')])
@input(['name' => 'to_url', 'label' => __('seo::error-redirect.to_url')])
@select([
    'name' => 'status_code',
    'label' => __('seo::error-redirect.status_code'),
    'options' => [
        ['value' => 301, 'label' => __('seo::error-redirect.status_codes.301')],
        ['value' => 302, 'label' => __('seo::error-redirect.status_codes.302')]
    ],
    'default' => 302
])
