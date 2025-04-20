<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';
    
    // Add this line to specify the correct table name
    protected $table = 'orderitems';
    
    // If your orderitems table doesn't have updated_at column, add this line
    const UPDATED_AT = null;
    const CREATED_AT = null;
    
    // Or if it doesn't have any timestamp columns, use this instead
    // public $timestamps = false;
    
    protected $fillable = [
        'order_id', 'variant_id', 'quantity', 'price'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }
}
