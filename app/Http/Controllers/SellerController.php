<?php

namespace App\Http\Controllers;

use App\Http\Requests\productRequest;
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


        return view('seller.index', compact('products', 'ordersCount'));
    }

    public function manageProducts()
    {
        $products = Auth::check() ? Product::where('user_id', Auth::user()->id)->get() : collect();
        return view('seller.manage-products', compact('products'));
    }

    public function addProduct()
    {
        return view('seller.add-product');
    }

    public function viewOrders()
    {
        $orders = Auth::check() ? Order::where('seller_id', Auth::id())->get() : collect();

        return view('seller.view-orders', compact('orders'));
    }

    public function storeProduct(ProductRequest $request)
    {
        Product::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'category_name'=>$request->category_name,
            'image_path'=>$request->image_path,
            'user_id' => Auth::user()->id,
        ]);
        return redirect()->route('seller.manage-products')->with('success', 'Product added successfully!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::user()->id) {
            return redirect()->route('seller.manage-products')->with('error', 'You cannot edit this product!');
        }

        $categories = Category::all();
        return view('seller.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::user()->id) {
            return redirect()->route('seller.manage-products')->with('error', 'You cannot edit this product!');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_name' => $request->category_name,
            'image_path' => $request->image_path,
        ]);

        return redirect()->route('seller.manage-products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id != Auth::id()) {
            return redirect()->route('seller.manage-products')->with('error', 'You cannot delete this product!');
        }

        $product->delete();

        return redirect()->route('seller.manage-products')->with('success', 'Product deleted successfully!');
    }
}
