<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{

    public function handle(Request $request, Closure $next)
    {
        //Check if the user is an admin
        if(Auth()->check())
        {
            if(Auth()->user()->role)
            {
                if(Auth()->user()->role->name == '1')
                {
                    return $next($request);
                }else{
                    return response()->json(['error' => 'Forbidden', 'status' => 403]);
                }
            }
        }
    }
}
