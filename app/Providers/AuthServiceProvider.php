<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Course;
use App\Models\CaseModel;
use App\Models\Certificate;
use App\Policies\CoursePolicy;
use App\Policies\CasePolicy;
use App\Policies\CertificatePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Course::class => CoursePolicy::class,
        CaseModel::class => CasePolicy::class,
        Certificate::class => CertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // تعريف صلاحيات عامة
        Gate::define('manage-courses', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('instructor');
        });

        Gate::define('manage-cases', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('judge');
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-system', function ($user) {
            return $user->hasRole('admin');
        });
    }
} 