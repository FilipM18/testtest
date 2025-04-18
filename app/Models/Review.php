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
    
    // getter na používateľa ktor hodnotenie napísal
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // getter na produkt, ktorý hodnotenie dostal
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
    // Upravenie formátu
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
    
    // Store hodnotenie pre produkt
    public function storeReviewForProduct($productId, $userId, $comment, $rating)
    {
        $this->product_id = $productId;
        $this->user_id = $userId;
        $this->comment = $comment;
        $this->rating = $rating;
        $this->save();
        
        // Prepočítanie hodnotenia produktu
        $product = Product::find($productId);
        $product->recalculateRating();
        
        return $this;
    }
}
