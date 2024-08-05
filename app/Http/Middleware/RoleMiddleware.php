<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
    public function handle($request, Closure $next, ...$roles)
    {        
        if(session()->has('AdminEmail') && $roles[0] == 1){
            return $next($request);
        }
        else if(session()->has('UserEmail') && $roles[0] == 2){               
            return $next($request);
        }        
        return redirect('/');
    }
}
