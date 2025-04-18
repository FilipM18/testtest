<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
    protected $primaryKey = 'product_id';
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
    // Na reviews pre produkt
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id');
    }

    public function recalculateRating()
    {
        //ešte pozri či nedať do controlleru
        $avgRating = $this->reviews()->avg('rating') ?: 0;
        $reviewCount = $this->reviews()->count();
        
        return $avgRating;
    }

}
