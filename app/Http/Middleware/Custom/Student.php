<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (Qs::userIsStudent()) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
