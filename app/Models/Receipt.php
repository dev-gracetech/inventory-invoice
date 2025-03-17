<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Receipt extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
    ];

    // Relationship to Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
