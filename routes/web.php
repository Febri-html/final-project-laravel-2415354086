<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\FrontendController;

/*
|--------------------------------------------------------------------------
| FRONTEND PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendController::class, 'customers']);
Route::get('/customers-page', [FrontendController::class, 'customers']);
Route::get('/services-page', [FrontendController::class, 'services']);
Route::get('/subscriptions-page', [FrontendController::class, 'subscriptions']);

/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::post('/customers-store', function (Request $request) {
    Customer::create([
        'customer_id' => $request->customer_id,
        'name'        => $request->name,
        'email'       => $request->email,
        'phone'       => $request->phone,
        'address'     => $request->address,
        'status'      => $request->status,
    ]);
    return back();
});
Route::put('/customers-setstatus/{id}', function (Request $request, $id) {
    Service::findOrFail($id)->update([
        'status' => $request->status
    ]);

    return back();
});

Route::put('/customers-update/{id}', function (Request $request, $id) {
    $customer = Customer::findOrFail($id);
    $customer->update([
        'customer_id' => $request->customer_id,
        'name'        => $request->name,
        'email'       => $request->email,
        'phone'       => $request->phone,
        'address'     => $request->address,
        'status'      => $request->status,
    ]);
    return back();
});

Route::delete('/customers-delete/{id}', function ($id) {
    Customer::find($id)?->delete();
    return back();
});

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

Route::post('/services-store', function (Request $request) {
    Service::create([
        'name'        => $request->name,
        'price'       => $request->price,
        'description' => $request->description,
        'status'      => $request->status,
    ]);
    return back();
});

Route::put('/services-update/{id}', function (Request $request, $id) {
    $service = Service::findOrFail($id);
    $service->update([
        'name'        => $request->name,
        'price'       => $request->price,
        'description' => $request->description,
        'status'      => $request->status,
    ]);
    return back();
});

Route::put('/services-setstatus/{id}', function (Request $request, $id) {
    Service::findOrFail($id)->update([
        'status' => $request->status
    ]);

    return back();
});

Route::delete('/services-delete/{id}', function ($id) {
    Service::find($id)?->delete();
    return back();
});
/*
/*
|--------------------------------------------------------------------------
| SUBSCRIPTION
|--------------------------------------------------------------------------
*/

Route::post('/subscriptions-store', [SubscriptionController::class, 'store']);
Route::put('/subscriptions-setstatus/{id}', [SubscriptionController::class, 'setStatus']);
Route::put('/subscriptions-update/{id}', [SubscriptionController::class, 'update']);