<?php

namespace App\Http\Middleware;

use Askedio\Laravel5GoogleCalendar\Calendar;
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
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        /* TO-DO: really needs a better solution. */
        Calendar::setVar('calendar', 'primary');
        Calendar::readCalendar();
        $errors = Calendar::$errors;
        if (is_array($errors)) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson()) {
                return response('Token Expired.', 401);
            } else {
                return redirect('/')->with(['error' => 'Token Expired']);
            }
        }

        return $next($request);
    }
}
