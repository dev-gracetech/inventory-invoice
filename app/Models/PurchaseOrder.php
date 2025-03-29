<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number', 
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'total_amount',
        'notes'
    ];
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
