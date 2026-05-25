<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Subscription::with(['customer', 'service'])->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id'  => 'required|exists:services,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:active,inactive,trial,isolir,dismantle',
        ]);

        $subscription = Subscription::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'data' => $subscription
        ], 201);
    }

    public function show($id)
    {
        $subscription = Subscription::with(['customer', 'service'])->find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        if (strtolower($subscription->status) === 'dismantle') {
            return response()->json([
                'success' => false,
                'message' => 'Dismantled subscription cannot be updated'
            ], 403);
        }

        $data = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'service_id'  => 'sometimes|exists:services,id',
            'start_date'  => 'sometimes|date',
            'end_date'    => 'sometimes|date|after_or_equal:start_date',
            'status'      => 'sometimes|in:active,inactive,trial,isolir,dismantle',
        ]);

        $subscription->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'data' => $subscription
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Subscription cannot be deleted. Use dismantle status instead.'
        ], 403);
    }

    public function setStatus(Request $request, $id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        if (strtolower($subscription->status) === 'dismantle') {
            return response()->json([
                'success' => false,
                'message' => 'Dismantled subscription cannot be changed'
            ], 403);
        }

        $data = $request->validate([
            'status' => 'required|in:active,inactive,trial,isolir,dismantle',
        ]);

        $subscription->update(['status' => $data['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription status updated successfully',
            'data' => $subscription
        ]);
    }
}