<div class="builder">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">{{ __('menu::menu.menu_item') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="dd" id="menuNestable"></div>
        </div>
        <div class="card-footer">
            <a href="{{ route('menu.admin.menu-item.create', ['menu_id' => $item->id]) }}" class="btn btn-outline-primary">
                <i class="fas fa-plus"></i>
                {{ __('menu::menu.create_item') }}
            </a>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var menuItems = @json($menuItems);
        var menuId = '{{ $item->id }}';
        var noLabel = `<span class="nn-no-translation">[{{ __('No Translation') }}]</span>`;
    </script>
@endpush

@assetadd('jquery.nestable', 'vendor/newnet-admin/plugins/nestable2/jquery.nestable.min.css')
@assetadd('jquery.nestable', 'vendor/newnet-admin/plugins/nestable2/jquery.nestable.min.js', ['jquery'])
@assetadd('toastr', 'vendor/newnet-admin/plugins/toastr/toastr.min.js', ['jquery'])
@assetadd('toastr', 'vendor/newnet-admin/plugins/toastr/toastr.css')
@assetadd('menu-builder', 'vendor/menu/js/admin/builder.js', ['jquery'])
@assetadd('menu-builder', 'vendor/menu/css/admin/builder.css')
