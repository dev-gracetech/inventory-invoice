<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'total_amount',
        'remaining_balance',
        'status',
    ];

    // Relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_items')
                    ->withPivot('quantity', 'unit_price', 'total_price')
                    ->withTimestamps();
    }

    // Relationship to Receipts
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
