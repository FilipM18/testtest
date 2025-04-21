<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    protected $table = 'orders';
    public $timestamps = true;
    
    protected $dates = [
        'created_at'
    ];

    protected $fillable = [
        'user_id', 'total_amount', 'status', 'payment_method', 'payment_status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
    
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }
}
