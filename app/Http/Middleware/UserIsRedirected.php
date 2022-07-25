<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserIsRedirected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAuthenticatedAdmin = (Auth::check());
  
        //This will be excecuted if the new authentication fails.
        if (! $isAuthenticatedAdmin){
          return redirect('login');
        }
        
        return $next($request);
        // print_r($request->all());die;
        // if ($request->session()->has('is_authorized') && $request->session()->get('is_authorized')) {
        //     return $next($request);
        // }
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // // return redirect(P2B_BASE_URL);
        // return route('login');
    }
}