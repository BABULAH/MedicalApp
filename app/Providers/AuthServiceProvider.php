<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Policies\DoctorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Doctor::class => DoctorPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
