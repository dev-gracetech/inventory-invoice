<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Customer extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
