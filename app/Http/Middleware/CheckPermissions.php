<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        /**
         * @var Check Permission for supplier
         */
        if ($request->user() && $request->user()->user_type == 'company') {
            return $next($request);
        } else if ($request->user() && $request->user()->user_type == 'employee') {
            if ($request->user()->userHasFormPermission($permission) || $request->user()->empHasFormPermission($permission)) {
                return $next($request);
            } else {
                return redirect()->back()->with("error", "You are not authorized to access this page.");
            }
        } else if ($request->user() && $request->user()->user_type == 'supplier') {
            $permissionArray = ["Manage Actions", "Complete Form", "Manage Document"];
            if (!in_array($permission, $permissionArray)) {
                return $next($request);
            }
            if ($request->user()->userHasFormPermission($permission)) {
                return $next($request);
            } else {
                return redirect()->back()->with("error", "You are not authorized to access this page.");
            }
        }
        return redirect("/");
    }
}