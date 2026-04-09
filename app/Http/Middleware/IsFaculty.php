<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Faculty;

class IsFaculty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is a faculty member
        $faculty = Faculty::where('user_id', auth()->id())->first();

        if (!$faculty) {
            // User is not a faculty, redirect to dashboard
            return redirect()->route('dashboard')->with('error', 'You do not have access to the faculty portal.');
        }

        // Share faculty data with request for easy access in controller/views
        $request->merge(['faculty' => $faculty]);

        return $next($request);
    }
}
