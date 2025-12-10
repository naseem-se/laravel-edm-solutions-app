<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkerMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'worker_mode') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only workers can access this route.'
            ], 403);
        }

        return $next($request);
    }
}
