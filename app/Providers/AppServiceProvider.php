<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider('portal', function ($app, array $config) {
            return new \App\Auth\MixedUserProvider;
        });

        Fortify::authenticateUsing(function (Request $request) {
            $identifier = $request->input(Fortify::username());
            $password = (string) $request->input('password');

            $candidates = [
                Siswa::query()->where('nis', $identifier)->first(),
                Admin::query()->where('username', $identifier)->first(),
            ];

            foreach (array_filter($candidates) as $candidate) {
                if (Hash::check($password, $candidate->password)) {
                    return $candidate;
                }
            }

            return null;
        });
    }
}
