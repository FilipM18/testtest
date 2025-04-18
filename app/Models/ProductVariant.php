<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    
    protected $table = 'productvariants';
    protected $primaryKey = 'variant_id';
    public $timestamps = false;
    
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'stock_quantity',
        'sku'
    ];
    
    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
