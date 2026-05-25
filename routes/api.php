<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('subscriptions', SubscriptionController::class);

Route::patch('/customers/{customer}/set-status', [CustomerController::class, 'setStatus']);
Route::patch('/services/{service}/set-status', [ServiceController::class, 'setStatus']);
Route::patch('/subscriptions/{subscription}/set-status', [SubscriptionController::class, 'setStatus']);