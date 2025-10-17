<div class="row">
    <div class="col-12 col-md-6">
        @input(['name' => 'name', 'label' => __('acl::role.name')])
        @textarea(['name' => 'description', 'label' => __('acl::role.description')])

        @if(is_admin())
            @checkbox(['name' => 'is_admin', 'label' => __('acl::role.is_admin')])
        @endif
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="permissions" class="font-weight-600">{{ __('acl::role.permissions') }}</label>
            <newnet-tree name="permissions"
                         class="nn-tree-permission"
                         :data='@json(Permission::allTreeWithoutKey())'
                         :value='@json(json_decode(object_get($item, 'permissions')))'
            ></newnet-tree>
            @error('permissions')
                <span class="invalid-feedback text-left">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
