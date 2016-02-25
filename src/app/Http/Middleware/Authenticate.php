<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        /* TO-DO: really needs a better solution. */
        \App\GoogleCalendar\Calendar::setVar('calendar', 'primary');
        \App\GoogleCalendar\Calendar::readCalendar();
        $errors = \App\GoogleCalendar\Calendar::$errors;
        if (is_array($errors)) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson()) {
                return response('Token Expired.', 401);
            } else {
                return redirect('/')->with(['error' => 'Token Expired']);
            }
        }

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}
