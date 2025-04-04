<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $products = Product::all(); // Fetch all products from the database
        return response()->json($products);
    }

    // Show product details
    public function showProduct(Product $product) // Use automatic model binding
    {
        return response()->json($product);
    }

    // Show the customer's shopping cart
    public function showCart()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $cart = $user->cart; // Get the products in the user's cart
        return response()->json($cart);
    }

    // Add a product to the shopping cart
    public function addToCart($productId)
    {
        $user = Auth::user(); // Get the currently authenticated user
        $product = Product::findOrFail($productId); // Find the product by its ID

        // Add the product to the cart and prevent duplicates
        $user->cart->syncWithoutDetaching($product);

        return response()->json(['message' => 'Product added to the cart successfully!']);
    }

    // Checkout and create an order
    public function checkout()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $cart = $user->cart; // Get the products in the cart

        if ($cart->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty!'], 400);
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => $cart->sum('price'),
        ]);

        // Add products to the OrderItem table with additional details
        foreach ($cart as $product) {
            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => 1, // Adjust based on your logic
                'price' => $product->price,
            ]);
        }

        // Clear the cart after placing the order
        $user->cart->detach();

        return response()->json(['message' => 'Order placed successfully!', 'order' => $order]);
    }

    // Show the customer's previous orders
    public function showOrders()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $orders = $user->orders; // Get all the orders of the user
        return response()->json($orders);
    }

    // Show order details
    public function showOrder($orderId)
    {
        $order = Order::findOrFail($orderId); // Fetch the order by its ID
        return response()->json($order);
    }
}
