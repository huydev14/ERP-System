<?php

namespace App\Services;

use App\Events\AuditLogEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log($description, $model, $logName = 'audit') {

        $causer = Auth::user();

        $properties = [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url'        => request()->fullUrl(),
            'method'     => request()->method(),
            'is_ajax'    => request()->ajax(),
            'session_id' => session()->getId(),
        ];

        AuditLogEvent::dispatch($description, $model,  $logName, $properties, $causer);
    }
}
