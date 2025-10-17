<div class="card h-100">
    <img src="{{ asset('vendor/dashboard/admin/img/cover.jpg') }}" alt="..." class="card-img-top">
    <div class="card-body text-center">
        <a href="{{ route('acl.admin.profile.index') }}" class="avatar avatar-xl card-avatar card-avatar-top mb-4">
            <img src="{{ current_admin()->avatar }}" class="avatar-img rounded-circle border-card" alt="{{ current_admin()->name }}">
        </a>
        <h5 class="card-title font-weight-600 mb-2">
            <a href="{{ route('acl.admin.profile.index') }}">
                {{ current_admin()->name }}
            </a>
        </h5>
        <p class="card-text text-muted mb-2">
            <span class="text-success">●</span> Online
        </p>
        <p class="card-text">
            @foreach(current_admin()->roles as $role)
                <span class="badge badge-pill badge-secondary">
                    {{ $role->name }}
                </span>
            @endforeach
        </p>
    </div>
</div>
