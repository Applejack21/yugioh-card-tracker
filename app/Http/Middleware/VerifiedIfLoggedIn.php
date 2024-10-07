<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class VerifiedIfLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a user is logged in.
        $user = Auth::user();

        // If there is no user let them in.
        if (! $user) {
            return $next($request);
        }

        // Check to see if:
        // we have a user
        // user is a type of the user model
        // and they aren't verified
        if (
            ! $user ||
            ($user instanceof User &&
                ! $user->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your email address is not verified.')
                : Redirect::guest(URL::route('verification.notice'));
        }

        // User is logged in and verified so let the in.
        return $next($request);
    }
}
