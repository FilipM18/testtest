<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    
    protected $table = 'cartitems';
    protected $primaryKey = 'cart_item_id';
    public $timestamps = false;
    
    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity'
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
