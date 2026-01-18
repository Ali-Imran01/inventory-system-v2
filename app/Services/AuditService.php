<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log a system action.
     *
     * @param string $action
     * @param array|null $payload
     * @return AuditLog
     */
    public static function log(string $action, ?array $payload = null)
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'payload' => $payload,
        ]);
    }
}
