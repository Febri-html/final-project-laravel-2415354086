<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Customer::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|string',
            'name'        => 'required|string',
            'email'       => 'nullable|email',
            'phone'       => 'nullable|string',
            'address'     => 'nullable|string',
            'status'      => 'required|boolean',
        ]);

        $customer = Customer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $data = $request->validate([
            'customer_id' => 'sometimes|string',
            'name'        => 'sometimes|string',
            'email'       => 'nullable|email',
            'phone'       => 'nullable|string',
            'address'     => 'nullable|string',
            'status'      => 'sometimes|boolean',
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }

    public function setStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|boolean'
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update(['status' => $data['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully',
            'data' => $customer
        ]);
    }
}