<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Interfaces\IOrderServices;
use App\Services\OrderServices;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private IOrderServices $orderServices;
    public function __construct(IOrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }
    public function index(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'customer_id' => 'sometimes|exists:customers,id',
            'status' => 'sometimes|string|in:pending,shipped',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $response = $this->orderServices->getAllOrders($request->all());
        if (!$response['success']) {
            return response()->json($response, 422);
        }
        return response()->json($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,shipped',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $response = $this->orderServices->createOrder($request->all());
        if (!$response['success']) {
            return response()->json($response, 422);
        }
        return response()->json($response, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $order)
    {
        $validated = \Validator::make($request->all(), [
            'product_name' => 'sometimes|string',
            'quantity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,shipped',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $response = $this->orderServices->updateOrder($order, $request->all());
        if (!$response['success']) {
            return response()->json($response, 422);
        }
        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function getOrderStats(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'customer_id' => 'sometimes|exists:customers,id',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $response = $this->orderServices->getOrderStats($request->all());
        if (!$response['success']) {
            return response()->json($response, 422);
        }
        return response()->json($response, 201);
    }
}
