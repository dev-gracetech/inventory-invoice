<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class QuotationItem extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['quotation_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    // Relationship with Quotation
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
