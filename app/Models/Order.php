<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    
    // Disable the timestamps feature completely
    public $timestamps = false;
    
    // Or alternatively, keep created_at but disable updated_at:
    // const UPDATED_AT = null;
    
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
}
