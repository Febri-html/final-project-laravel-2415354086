<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'service_id'  => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:active,inactive,trial,isolir,dismantle',
        ]);

        Subscription::create([
            'customer_id' => $request->customer_id,
            'service_id'  => $request->service_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => $request->status,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        if (strtolower($subscription->status) === 'dismantle') {
            return back()->with('error', 'Subscription yang sudah dismantle tidak bisa diedit.');
        }

        $request->validate([
            'customer_id' => 'required',
            'service_id'  => 'required',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:active,inactive,trial,isolir,dismantle',
        ]);

        $subscription->update([
            'customer_id' => $request->customer_id,
            'service_id'  => $request->service_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'status'      => $request->status,
        ]);

        return back();
    }

    public function setStatus(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        if (strtolower($subscription->status) === 'dismantle') {
            return back()->with('error', 'Subscription yang sudah dismantle tidak bisa diubah.');
        }

        $request->validate([
            'status' => 'required|in:active,inactive,trial,isolir,dismantle',
        ]);

        $subscription->update([
            'status' => $request->status,
        ]);

        return back();
    }
}