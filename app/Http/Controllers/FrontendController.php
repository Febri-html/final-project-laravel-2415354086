<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;

class FrontendController extends Controller
{
    public function customers()
    {
        $customers = Customer::latest()->get();
        return view('customers', compact('customers'));
    }

    public function services()
    {
        $services = Service::latest()->get();
        return view('services', compact('services'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with(['customer', 'service'])->latest()->get();
        $customers = Customer::where('status', true)->get();
        $services = Service::where('status', true)->get();

        return view('subscriptions', compact('subscriptions', 'customers', 'services'));
    }
}