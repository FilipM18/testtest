<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Na zobrazenie formulára pre pridanie review
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('reviews.create', compact('product'));
    }
    
    //Store na nový review
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);
        
        $review = new Review();
        $review->storeReviewForProduct(
            $request->product_id,
            Auth::id(),
            $request->comment,
            $request->rating
        );
        
        return redirect()->route('products.show', $request->product_id)
            ->with('success', 'Your review has been submitted successfully!');
    }
}
