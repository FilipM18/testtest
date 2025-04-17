<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    
    // Match the table name exactly as in Supabase
    protected $table = 'cartitems';
    
    // Use the primary key from the schema
    protected $primaryKey = 'cart_item_id';
    
    // Disable timestamps since they don't match the schema
    public $timestamps = false;
    
    // List all fillable fields that match your Supabase table
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
