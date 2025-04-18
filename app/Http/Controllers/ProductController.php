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
        //Načítanie produktu podľa ID 
        $product = Product::with(['brand', 'reviews.user', 'variants'])
            ->where('product_id', $id)
            ->first();
        
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
        
        // Vypočítaj priemerné hodnotenie a počet recenzií
        $avgRating = $product->reviews->avg('rating') ?: 0;
        $reviewCount = $product->reviews->count();
        
        // Pridaj tieto hodnoty do objektu produktu
        $product->average_rating = $avgRating;
        $product->review_count = $reviewCount;

        $variants = ProductVariant::where('product_id', $id)->get();
        
        return view('ProductInfo', compact('product', 'variants'));
    }


}
