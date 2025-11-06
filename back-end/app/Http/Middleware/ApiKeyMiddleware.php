<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for the API key in the request headers
        $apiKey = $request->header('X-API-KEY');

        // Validate against a list of allowed keys stored in env or DB
        $validApiKeys = [
            '197d6e4645a497dda4015979efa585d4',
            'bcbbba2019eeb263a7df36b3bae8c3c8',
        ];

        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json([
                'message' => 'Invalid API key',
                'status' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
