<div class="card h-100">
    <div class="card-body text-center">
        <div class="row justify-content-center">
            <div class="greet-user col-12 col-xl-10">
                <img src="{{ asset('vendor/newnet-admin/dist/img/happiness.svg') }}" alt="Dashboard" class="img-fluid mb-2">
                <h2 class="fs-23 font-weight-600 mb-2">
                    {{ __('dashboard::dashboard.welcome.hello', ['name' => get_current_admin()->name]) }}
                </h2>
                <p class="text-muted">
                    {{ strip_tags(\Illuminate\Foundation\Inspiring::quote()) }}
                </p>
            </div>
        </div>
    </div>
</div>
