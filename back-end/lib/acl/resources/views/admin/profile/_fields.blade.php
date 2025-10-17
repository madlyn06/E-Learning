<ul class="nav nav-pills scrollable">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#profileInfo">
            {{ __('acl::profile.info') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#profilePassword">
            {{ __('acl::profile.change-password') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="profileInfo">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-2">
                @mediafile(['name' => 'avatar', 'label' => __('acl::profile.avatar'), 'fileManager' => false])
            </div>
            <div class="col-12 col-md-8 col-lg-4">
                @input(['name' => 'name', 'label' => __('acl::user.name')])
                @input(['name' => 'email', 'label' => __('acl::user.email'), 'type' => 'email'])
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="profilePassword">
        @input(['name' => 'password', 'label' => __('acl::profile.new-password'), 'type' => 'password'])
        @input(['name' => 'password_confirmation', 'label' => __('acl::profile.password_confirmation'), 'type' => 'password'])
    </div>
</div>
