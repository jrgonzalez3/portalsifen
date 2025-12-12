<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo auto-login en entorno local/development
        if (app()->environment('local') && config('sifen.auto_login.enabled')) {
            if (!auth()->check()) {
                $user = \App\Models\User::where('email', config('sifen.auto_login.email'))->first();
                
                if ($user) {
                    auth()->login($user);
                }
            }
        }
        
        return $next($request);
    }
}
