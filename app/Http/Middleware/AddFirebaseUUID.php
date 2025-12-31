<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddFirebaseUUID
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only modify JSON responses
        if (!$response instanceof Response ||
            !str_contains($response->headers->get('Content-Type'), 'application/json')) {
            return $response;
        }

        $data = $response->getData(true);

        // Get firebase uuid (header / auth user)
        $firebaseUID =
            $request->header('firebase-uid')
            ?? optional(auth()->user())->firebase_uid;

        // Add it to response
        $data['firebase_uid'] = $firebaseUUID;

        return response()->json(
            $data,
            $response->getStatusCode(),
            $response->headers->all()
        );
    }
}
