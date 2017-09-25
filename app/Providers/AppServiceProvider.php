<?php

namespace JP_COMMUNITY\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use JP_COMMUNITY\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('user_role_admin', function ($attribute, $values, $parameters, $validator) {
            $role_admin = array_keys(User::userType()['admin']);

            if (!in_array($values, $role_admin)) {
                return false;
            }
            return true;
        });

        Validator::extend('user_role_client', function ($attribute, $values, $parameters, $validator) {
            $role_client = array_keys(User::userType()['client']);

            if (!in_array($values, $role_client)) {
                return false;
            }
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
