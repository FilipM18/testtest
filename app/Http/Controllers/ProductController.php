<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function show($id)
    {
        // Load the product with its brand, reviews, and the users who wrote those reviews
        $product = Product::with(['brand', 'reviews.user', 'variants'])
            ->where('product_id', $id)
            ->first();
        
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
        
        // Calculate average rating and review count
        $avgRating = $product->reviews->avg('rating') ?: 0;
        $reviewCount = $product->reviews->count();
        
        // Add these calculated values to the product object
        $product->average_rating = $avgRating;
        $product->review_count = $reviewCount;
        
        // Get variants if needed
        $variants = ProductVariant::where('product_id', $id)->get();
        
        return view('ProductInfo', compact('product', 'variants'));
    }


}
