<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;

class Authenticate extends Middleware
{
    /**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, Closure $next) {
        $isAuthenticatedAdmin = (Auth::check());
  
        //This will be excecuted if the new authentication fails.
        // if (! $isAuthenticatedAdmin){
        //   return redirect('login');
        // }
        
        return $next($request);
	}


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}