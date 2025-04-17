<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $table = 'carts';
    protected $primaryKey = 'cart_id';
    
    // Only created_at exists in the schema
    const UPDATED_AT = null;
    
    protected $fillable = [
        'user_id',
        'created_at'
    ];
    
    // Relationships
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'cart_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
