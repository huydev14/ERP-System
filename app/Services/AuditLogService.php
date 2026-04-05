<?php

namespace App\Services;

use App\Events\AuditLogEvent;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public static function log($description, $model, $logName = 'audit') {

        $causer = auth()->user();
        
        $properties = [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url'        => request()->fullUrl(),
            'method'     => request()->method(),
            'is_ajax'    => request()->ajax(),
            'session_id' => session()->getId(),
        ];

        AuditLogEvent::dispatch($description, $model,  $logName, $properties, $causer)
    }
}
