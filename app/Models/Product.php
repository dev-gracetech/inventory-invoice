<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'buying_price',
        'selling_price',
        'quantity',
        'category',
        'image',
        'status',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('images/products/' . $this->image);
        }
        return asset('images/default_stock_image.png'); // Path to the default image
    }
}
