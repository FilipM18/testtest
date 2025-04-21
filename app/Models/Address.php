<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'address_id';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'country', 'city', 'street', 'zip_code'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
