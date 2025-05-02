<?php
namespace App\Services;

use App\Models\Order;
use DB;

class OrderServices implements Interfaces\IOrderServices
{
  public function createOrder(array $data): array
  {
    // Validate and create order logic here
    DB::beginTransaction();
    try {
      Order::create($data);
      DB::commit();
      return ['success' => true, 'message' => 'Order created successfully'];
    } catch (\Exception $e) {
      DB::rollBack();
      // Log the error message for debugging
      \Log::error('Order creation failed: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Failed to create order: '];
    }
  }

  public function updateOrder(int $id, array $data): array
  {
    DB::beginTransaction();
    try {
      $order = Order::findOrFail($id);
      $order->update($data);
      DB::commit();
      // Validate and update order logic here
      return ['success' => true, 'message' => 'Order updated successfully'];
    } catch (\Exception $e) {
      DB::rollBack();
      // Log the error message for debugging
      \Log::error('Order update failed: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Failed to update order: '];
    }
  }

  public function getAllOrders(array $data): array
  {
    try {
      // Fetch all orders logic here
      $orders = Order::with('customer')
        ->when(isset($data['customer_id']), function ($query) use ($data) {
          return $query->where('customer_id', $data['customer_id']);
        })
        ->when(isset($data['status']), function ($query) use ($data) {
          return $query->where('status', $data['status']);
        })
        ->orderBy('created_at', 'desc')
        ->get();
      return ['success' => true, 'orders' => $orders];
    } catch (\Exception $e) {
      // Log the error message for debugging
      \Log::error('Failed to fetch orders: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Failed to fetch orders'];
    }
  }

  public function getOrderStats(array $data): array
  {
    try {
      // Fetch order statistics logic here
      $ordersPerStatus = Order::select('status', DB::raw('count(*) as count'))
        ->when(isset($data['customer_id']), function ($query) use ($data) {
          return $query->where('customer_id', $data['customer_id']);
        })
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();
      $totalRevenue = Order::when(isset($data['customer_id']), function ($query) use ($data) {
        return $query->where('customer_id', $data['customer_id']);
      })->sum(DB::raw('price * quantity'));
      return ['success' => true, 'stats' => ['ordersPerStatus' => $ordersPerStatus, 'total_revenue' => $totalRevenue]];
    } catch (\Exception $e) {
      // Log the error message for debugging
      \Log::error('Failed to fetch order statistics: ' . $e->getMessage());
      return ['success' => false, 'message' => 'Failed to fetch order statistics'];
    }
  }
}