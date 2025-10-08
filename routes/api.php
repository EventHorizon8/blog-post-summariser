<?php


use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/summarize', [\App\Http\Controllers\ContentController::class, 'summarize']);
