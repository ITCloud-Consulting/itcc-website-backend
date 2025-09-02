<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateCsrfForSpa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = [
            config('app.frontend_url'),
            'http://localhost:3000',
            'http://localhost:8000',
            'http://127.0.0.1:3000',
            'http://127.0.0.1:8000',
            'https://itcloudconsultings.com',
            'http://192.168.65.1:3000',
            'http://192.168.65.1:8000',
        ];

        $origin = $request->header('origin');

        if ($origin && !in_array($origin, $allowedOrigins)) {
            return response()->json([
                'message' => 'Origin not allowed',
            ], 401);
        }
        return $next($request);
    }
}
