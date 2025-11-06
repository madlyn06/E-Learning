<?php

namespace Modules\Elearning;

use Modules\Elearning\Services\LessonService;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Modules\Elearning\Services\AssignmentService;
use Modules\Elearning\Services\QuizService;

class ElearningServiceProvider extends BaseModuleServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        RateLimiter::for('elearning-auth', function ($request) {
            $perMinute = (int) config('app.request_per_minute', 60);
            // throttle by API key if present, else fall back to IP
            $key = $request->header('X-API-KEY') ?: $request->ip();
            return Limit::perMinute($perMinute)->by($key);
        });
    }

    public function register(): void
    {
        parent::register();
        $this->registerBindings();
        $this->app->register(\Modules\Elearning\Providers\EventServiceProvider::class);
        require_once __DIR__.'/../helpers/helpers.php';
    }

    protected function registerBindings(): void
    {
        $this->app->singleton(\Modules\Elearning\Services\MailServiceInterface::class, function ($app) {
            return new \Modules\Elearning\Services\MailService();
        });

        $this->app->singleton(\Modules\Elearning\Interfaces\CategoryServiceInterface::class, function ($app) {
            return new \Modules\Elearning\Services\CategoryService();
        });

        $this->app->singleton(\Modules\Elearning\Services\Payment\PaymentService::class, function ($app) {
            return new \Modules\Elearning\Services\Payment\PaymentService();
        });
        
        $this->app->singleton(\Modules\Elearning\Services\CouponService::class, function ($app) {
            return new \Modules\Elearning\Services\CouponService();
        });
        
        $this->app->singleton(\Modules\Elearning\Services\EnrollmentService::class, function ($app) {
            return new \Modules\Elearning\Services\EnrollmentService(
                $app->make(\Modules\Elearning\Services\CouponService::class),
                $app->make(\Modules\Elearning\Services\Payment\PaymentService::class)
            );
        });

        $this->app->bind(AssignmentService::class, AssignmentService::class);
        $this->app->bind(QuizService::class, QuizService::class);
        $this->app->bind(LessonService::class, LessonService::class);

        // Register Video Service Manager
        $this->app->singleton(\Modules\Elearning\Services\Video\VideoServiceManager::class, function ($app) {
            return new \Modules\Elearning\Services\Video\VideoServiceManager();
        });

        // Register Video Service Manager as 'video' for easier access
        $this->app->alias(\Modules\Elearning\Services\Video\VideoServiceManager::class, 'video');

        $this->app->singleton(\Modules\Elearning\Strategy\Upload\UploadManager::class, function ($app) {
            return new \Modules\Elearning\Strategy\Upload\UploadManager($app);
        });
    }
}
