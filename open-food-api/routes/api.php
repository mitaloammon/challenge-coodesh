<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\ProductController;
use App\Http\Controllers\SystemController;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'db_connection' => DB::connection()->getDatabaseName(),
        'cron_last_execution' => optional(App\Models\Import::latest('executed_at')->first())->executed_at,
        'uptime' => now()->diffForHumans(app()->startTime()),
        'memory_usage' => memory_get_usage(true)
    ]);
});

Route::get('/', [SystemController::class, 'status']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{code}', [ProductController::class, 'show']);
Route::put('/products/{code}', [ProductController::class, 'update']);
Route::delete('/products/{code}', [ProductController::class, 'destroy']);

Route::post('/import', [ProductController::class, 'importProducts']);