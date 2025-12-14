<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Lis\OrderLabDetailController;

Route::get('/lis/order-lab-detail', [OrderLabDetailController::class, 'index']);

// Route::get('/ping', function () {
//     return response()->json([
//         'status'  => 'ok',
//         'app'     => 'SIMGOS',
//         'message' => 'API LIS aktif',
//         'time'    => now()->toDateTimeString(),
//     ]);
// });