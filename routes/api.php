<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SubscriptionController;

Route::apiResource('services', ServiceController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('subscriptions', SubscriptionController::class);

Route::patch('services/{service}/activate', [
    ServiceController::class,
    'activate',
]);

Route::patch('services/{service}/deactivate', [
    ServiceController::class,
    'deactivate',
]);