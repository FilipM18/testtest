<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cartitems';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    
    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity',
        'user_id'
    ];
    
    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }
    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }
}
