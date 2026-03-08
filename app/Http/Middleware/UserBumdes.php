<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserBumdes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            $slug = $request->route('slug');
            
            // Security: Ensure user can only access their own dashboard/slug
            if ($slug && Auth::user()->username !== $slug) {
                return redirect()->route('user.dashboard', ['slug' => Auth::user()->username]);
            }

            // Set default parameters for URL generator so we don't need to pass 'slug' in every route() call
            \Illuminate\Support\Facades\URL::defaults(['slug' => Auth::user()->username]);

            return $next($request);
        }
        
        return redirect()->route('login')->with('error', 'You do not have access to this section.');
    }
}
