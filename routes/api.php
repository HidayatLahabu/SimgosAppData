<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Lis\OrderLabDetailController;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'app' => 'SIMGOS',
        'message' => 'API LIS aktif',
        'time' => now()->toDateTimeString(),
    ]);
});

Route::get('/lis/order-lab-detail', [OrderLabDetailController::class, 'index']);
Route::get('/lis/order-lab-detail/{orderId}', [OrderLabDetailController::class, 'show']);
