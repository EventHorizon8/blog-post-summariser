<?php


use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/summaries', [\App\Http\Controllers\ContentController::class, 'summarize']);

Route::get('/summaries', [\App\Http\Controllers\ContentController::class, 'index']);
