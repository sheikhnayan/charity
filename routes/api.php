<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageBuilderController;

Route::middleware('auth')->group(function () {
    Route::post('/page-builder/save', [PageBuilderController::class, 'save']);
    Route::get('/page-builder/load', [PageBuilderController::class, 'load']);
});
