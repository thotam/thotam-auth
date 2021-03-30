<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $contains = Str::contains(url()->current(), ['email/verify', 'forgot-password', 'login', 'register', 'logout', 'reset-password', 'two-factor-challenge ','user/confirm-password', 'user/confirmed-password-status ', 'user/password', 'user/profile-information', 'user/two-factor-authentication', 'user/two-factor-qr-code', 'user/two-factor-recovery-codes']);
            if ($contains) {
                return route('login');
            } else {
                return route('login', ['urlback' => url()->current()]);
            }
        }
    }
}
