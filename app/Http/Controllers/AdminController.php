<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Display the admin dashboard
    public function dashboard()
    {
        // Fetch data for the dashboard (user count, product count, order count)
        $userCount = User::count();
        $productCount = Product::count();
        $orderCount = Order::count();

        return response()->json([
            'productCount' => $productCount,
            'userCount' => $userCount,
            'orderCount' => $orderCount,
        ]);
    }

    // Manage users (view all users)
    public function Manageusers()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }

    // Manage products (view all products)
    public function Manageproducts()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

    // Manage orders (view all orders)
    public function Manageorders()
    {
        $orders = Order::with('user')->paginate(10);
        return response()->json($orders);
    }

    // Display a list of categories
    public function manageCategories()
    {
        $categories = Category::paginate(10);  // Fetching all categories
        return response()->json($categories);
    }

    // Show the form for creating a new category
    public function createCategory()
    {
        return view('admin.create-category');
    }

    // Store a newly created category
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category,
        ]);
    }

    // Show the form for editing an existing category
    public function editCategory(Category $category)
    {
        return view('admin.edit-category', compact('category'));
    }

    // Update an existing category
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category,
        ]);
    }

    // Delete a category
    public function destroyCategory(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
