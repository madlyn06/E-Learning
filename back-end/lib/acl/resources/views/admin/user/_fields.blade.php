<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#manage_admin_info">
            {{ __('acl::user.tab.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link save-tab" data-toggle="pill" href="#manage_admin_seo">
            {{ __('acl::user.tab.seo') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="manage_admin_info">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12 col-md-6">
                        @input(['name' => 'name', 'label' => __('acl::user.name'), 'required' => true])
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="email" name="email" autocomplete="email" disabled style="height: 0px; border: 0; opacity: 0; position: absolute;">
                        @input(['name' => 'email', 'label' => __('acl::user.email'), 'type' => 'email', 'required' => true])
                    </div>
                </div>
                @input(['name' => 'phone', 'label' => __('acl::user.phone')])
                @slug(['name' => 'slug', 'label' => __('acl::user.slug'), 'slugFrom' => '#name', 'required' => true])

                <input type="password" autocomplete="password" disabled style="height: 0px; border: 0; opacity: 0; position: absolute;">
                @input(['name' => 'password', 'label' => __('acl::user.password'), 'type' => 'password', 'required' => true])

                @input(['name' => 'address', 'label' => __('acl::user.address')])

                @input(['name' => 'title', 'label' => __('acl::user.title')])

                @sumoselect(['name' => 'roles', 'label' => __('acl::user.roles'), 'options' => get_acl_role_options(), 'multiple' => true])

                <div class="row">
                    <div class="col-12 col-md-6">
                        @if(is_admin())
                        @checkbox(['name' => 'is_admin', 'label' => '', 'placeholder' => __('acl::user.is_admin')])
                        @endif </div>
                    <div class="col-12 col-md-6">
                        @checkbox(['name' => 'is_active', 'label' => '', 'placeholder' => __('acl::user.is_active'), 'default' => true])
                    </div>
                </div>
                @mediafile(['name' => 'avatar', 'label' => __('acl::user.avatar')])
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="permissions" class="font-weight-600">{{ __('acl::user.permissions') }}</label>
                    <newnet-tree name="permissions" class="nn-tree-permission" :data='@json(Permission::allTreeWithoutKey())' :value='@json(json_decode(object_get($item, 'permissions')))'></newnet-tree>
                    @error('permissions')
                    <span class="invalid-feedback text-left">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <hr />
                <div class="form-group">
                    @textarea(['name' => 'description', 'label' => __('acl::user.description')])
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        @input(['name' => 'facebook', 'label' => __('acl::user.facebook')])
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['name' => 'instagram', 'label' => __('acl::user.instagram')])
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        @input(['name' => 'youtube', 'label' => __('acl::user.youtube')])
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['name' => 'pinterest', 'label' => __('acl::user.pinterest')])
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        @input(['name' => 'linkedin', 'label' => __('acl::user.linkedin')])
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['name' => 'twitter', 'label' => __('acl::user.twitter')])
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12">
            @editor(['name' => 'content', 'label' => __('cms::post.content')])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="manage_admin_seo">
        @seo
    </div>
</div>
