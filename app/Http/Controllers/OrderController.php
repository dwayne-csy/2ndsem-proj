<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Display all orders (Admin)
    public function index()
    {
        $orders = Order::with('user', 'orderLines.product')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Display user's order history (without cancellation)
    public function history()
    {
        $orders = auth()->user()->orders()
                    ->with(['orderLines.product'])
                    ->latest()
                    ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    // Show specific order details (view only)
    public function show(Order $order)
    {
        // Authorization - user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    
        $order->load(['orderLines.product', 'user']);
    
        return view('orders.show', compact('order'));
    }

    // Accept an order (Admin only)
    public function accept($id)
    {
        DB::beginTransaction();
        
        try {
            $order = Order::with('orderLines.product')->findOrFail($id);
    
            // Only allow accepting pending orders
            if ($order->status !== Order::STATUS_PENDING) {
                return redirect()->route('admin.orders.index')->with('error', 'Only pending orders can be accepted.');
            }
            
            // Update order status
            $order->update(['status' => Order::STATUS_ACCEPTED]);
            
            // Decrease stock for each product in the order
            foreach ($order->orderLines as $orderLine) {
                $product = $orderLine->product;
                
                if ($product) {
                    // Check if enough stock is available
                    if ($product->stock < $orderLine->quantity) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Insufficient stock for product: {$product->name}");
                    }
                    
                    // Decrease the stock
                    $product->decrement('stock', $orderLine->quantity);
                }
            }
            
            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order accepted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.orders.index')->with('error', 'Failed to accept order: ' . $e->getMessage());
        }
    }
    
    // Cancel an order (Admin only)
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
    
        // Only allow cancelling pending orders
        if ($order->status === Order::STATUS_PENDING) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
            return redirect()->route('admin.orders.index')->with('success', 'Order cancelled successfully!');
        }
    
        return redirect()->route('admin.orders.index')->with('error', 'Only pending orders can be cancelled.');
    }
}