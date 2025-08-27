<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/contact', [ContactController::class, 'store'])
         ->middleware('throttle:5,1'); // 5 requêtes par minute
    
    // Admin routes (protégées)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/contacts', [ContactController::class, 'index']);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0')
    ]);
});