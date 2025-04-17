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
        // Add debugging
        Log::info('Product ID requested: ' . $id);
        
        // Find the product by its ID
        $product = Product::findOrFail($id);
        
        if (!$product) {
            Log::info('Product not found with ID: ' . $id);
            abort(404, 'Product not found');
        }
        
        Log::info('Product found: ' . $product->name);
        
        // Get all variants for this product
        $variants = ProductVariant::where('product_id', $product->product_id)->get();
        
        // Return the product info view with product and variants data
        return view('ProductInfo', compact('product', 'variants'));
    }

}
