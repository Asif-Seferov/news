<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       // $userRoles = Auth::user()->roles;
        //$userRoles = Auth::user()->getRolId->rol_name;
        //dd($userRoles);
          /*  if($userRoles->contains('Admin')){
            return redirect()->route('login')->with('error', 'Sizin girmək səlahiyətiniz yoxdur');
        }
        return $next($request);  */  

    }
}
