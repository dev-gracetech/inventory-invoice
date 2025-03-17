<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class InvoiceItem extends Model
{
    use Auditable;
    
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    // Relationship with Invoice
    public function invoice()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}