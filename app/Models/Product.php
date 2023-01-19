<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','user_id', 'category', 'quantity','stock_price','sell_price'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
