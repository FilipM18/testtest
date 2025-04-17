<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'review_id';
    
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];
    
    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * Get the product that this review belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
    /**
     * Format the created_at date for display
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
    
    /**
     * Store a new review for a product
     */
    public function storeReviewForProduct($productId, $userId, $comment, $rating)
    {
        $this->product_id = $productId;
        $this->user_id = $userId;
        $this->comment = $comment;
        $this->rating = $rating;
        $this->save();
        
        // Recalculate the product's average rating
        $product = Product::find($productId);
        $product->recalculateRating();
        
        return $this;
    }
}
