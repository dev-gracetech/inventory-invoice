<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Refund extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'invoice_id',
        'amount_refunded',
        'refund_date',
        'refund_method',
        'reference_number',
        'notes',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
