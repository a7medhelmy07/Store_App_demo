<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class SellerController extends Controller
{
    public function index()
    {
        $products = Auth::user()->products;
        $ordersCount = Auth::check() ? Order::where('seller_id', Auth::id())->count() : 0;

        return response()->json([
            'products' => $products,
            'ordersCount' => $ordersCount,
        ]);
    }

    public function manageProducts()
    {
        $products = Auth::check() ? Product::where('user_id', Auth::user()->id)->get() : collect();
        return response()->json($products);
    }

    public function addProduct()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function viewOrders()
    {
        $orders = Auth::check() ? Order::where('seller_id', Auth::id())->get() : collect();

        return response()->json($orders);
    }

    public function storeProduct(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_name' => $request->category_name,
            'image_path' => $request->image_path,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ]);
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::user()->id) {
            return response()->json(['error' => 'You cannot edit this product!'], 403);
        }

        $categories = Category::all();
        return response()->json([
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function updateProduct(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::user()->id) {
            return response()->json(['error' => 'You cannot edit this product!'], 403);
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_name' => $request->category_name,
            'image_path' => $request->image_path,
        ]);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::id()) {
            return response()->json(['error' => 'You cannot delete this product!'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully!']);
    }
}
