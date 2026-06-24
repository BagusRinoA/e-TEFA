<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Product::where('stock', '>', 0)->distinct()->pluck('category');

        $products = Product::query()
            ->where('stock', '>', 0)
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}
