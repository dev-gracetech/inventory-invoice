<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['action', 'model', 'model_id', 'changes', 'user_id'];

    // Relationship with the user who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}