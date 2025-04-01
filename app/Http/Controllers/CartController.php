<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order; 
use App\Models\OrderLine;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Add a product to the cart
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');  // Get the product_id from the form
        
        if (!$productId) {
            return redirect()->route('cart.index')->with('error', 'Product ID is missing.');
        }
    
        // Get the authenticated user
        $userId = auth()->id();
    
        // Find the product using the product_id column
        $product = Product::where('product_id', $productId)->first();  // Use product_id here
    
        if (!$product) {
            return redirect()->route('cart.index')->with('error', 'Product not found.');
        }
    
        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $productId)  // Use product_id here
                        ->first();
    
        if ($cartItem) {
            // If product is already in cart, increase quantity
            $cartItem->quantity++;
            $cartItem->save();
        } else {
            // If product is not in cart, add a new entry
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }
    
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // View the cart items
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }
    
    // Remove a product from the cart
    public function removeFromCart($id)
    {
        $cartItem = Cart::find($id);

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
        }

        return redirect()->route('cart.index')->with('error', 'Product not found in cart.');
    }

    // Update a cart
    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $newQuantity = max(1, $request->quantity);
        $cartItem->update(['quantity' => $newQuantity]);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function checkout(Request $request)
    {
        // Get the authenticated user's cart items
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
    
        // Calculate the total price using sell_price
        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->sell_price);
    
        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => Order::STATUS_PENDING, // Default status is pending
        ]);
    
        // Create order lines
        foreach ($cartItems as $item) {
            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'sell_price' => $item->product->sell_price, // Use sell_price
            ]);
        }
    
        // Clear the cart
        Cart::where('user_id', auth()->id())->delete();
    
        // Redirect to a success page
        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }



}