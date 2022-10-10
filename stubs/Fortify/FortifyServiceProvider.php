<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // register new LogoutResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \Thotam\ThotamAuth\Http\Responses\LogoutResponse::class
        );

        // register new RegisterResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \Thotam\ThotamAuth\Http\Responses\RegisterResponse::class
        );
        Fortify::registerView(fn () => view('thotam-auth::auth.register', ['urlback' => request("urlback")]));

        // register new LoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \Thotam\ThotamAuth\Http\Responses\LoginResponse::class
        );
        Fortify::loginView(fn () => view('thotam-auth::auth.login', ['urlback' => request("urlback")]));

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });

        Fortify::requestPasswordResetLinkView(fn () => view('thotam-auth::auth.password-reset'));

        Fortify::resetPasswordView(fn ($request) => view('thotam-auth::auth.password-resetting', ['request' => $request]));
    }
}
