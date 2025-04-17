<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review; // Ensure the Review model exists in this namespace

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    
    // Only created_at exists in the schema
    const UPDATED_AT = null;
    
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'price',
        'gender',
        'type',
        'image_url',
        'created_at',
        'active'
    ];
    
    // Relationships
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }
    /**
     * Get the reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    /**
     * Recalculate the average rating for this product
     */
    public function recalculateRating()
    {
        // This method is optional if you're calculating ratings on-the-fly in the controller
        // But it can be useful if you want to store the average rating in the products table
        
        $avgRating = $this->reviews()->avg('rating') ?: 0;
        $reviewCount = $this->reviews()->count();
        
        // If you have rating_avg and review_count columns in your products table:
        // $this->rating_avg = $avgRating;
        // $this->review_count = $reviewCount;
        // $this->save();
        
        return $avgRating;
    }

}
