<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return response()->json($logs);
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return response()->json($auditLog);
    }
}
