<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        
        // Cek jika ada filter kategori
        $categoryId = $request->query('category');
        // Cek jika ada pencarian
        $search = $request->query('search');
        
        $products = Product::with(['category', 'variants'])
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(12);
            
        return view('shop.index', compact('categories', 'products'));
    }

    public function show($slug): View
    {
        $product = Product::with(['category', 'variants'])->where('slug', $slug)->firstOrFail();
        
        // Ambil beberapa produk terkait di kategori yang sama
        $relatedProducts = Product::with('variants')->where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
                                
        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
