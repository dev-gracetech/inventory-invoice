<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    // Log changes when a model is created, updated, or deleted
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAction('created');
        });

        static::updated(function ($model) {
            $model->logAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
        
    }

    // Log the action
    protected function logAction($action)
    {
        $changes = $this->getChangesForLog($action);

        AuditLog::create([
            'action' => $action,
            'model' => get_class($this),
            'model_id' => $this->id,
            'changes' => json_encode($changes),
            'user_id' => Auth::id(), // Log the user who performed the action
        ]);
    }

    // Get changes for the log
    protected function getChangesForLog($action)
    {
        if ($action === 'created') {
            return $this->getAttributes(); // Log all attributes for new records
        }

        if ($action === 'updated') {
            return $this->getChanges(); // Log only changed attributes
        }

        return null; // No changes for deleted records
    }
}