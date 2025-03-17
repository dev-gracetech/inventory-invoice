<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Quotation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['quotation_number', 'customer_id', 'quotation_date', 'expiry_date', 'total_amount', 'notes' , 'status'];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with QuotationItems
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
