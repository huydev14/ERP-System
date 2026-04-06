<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit-logs.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $logs = Activity::with(['causer', 'subject'])->latest()->get();

            return DataTables::of($logs)
                ->editColumn('created_at', function ($log) {
                    return Carbon::parse($log->created_at)->format('H:i - d/m/Y');
                })
                ->editColumn('causer_name', function ($log) {
                    return $log->causer?->name;
                })
                ->editColumn('subject_id', function ($log) {
                    if (!$log->subject_type) return 'N/A';

                    $modelName = class_basename($log->subject_type);
                    return '<span class="tw-text-xs tw-font-mono tw-bg-gray-100 tw-px-1.5 tw-py-0.5 tw-rounded">
                            ' . $modelName . ' #' . $log->subject_id . '
                            </span>';
                })
                ->editColumn('session_id', function ($log) {
                    return $log->getExtraProperty('session_id');
                })
                ->addColumn('details', function ($log) {
                    if (!empty($log->properties->all())) {
                        return '<button type="button" class="view-detail-btn tw-text-p hover:tw-underline tw-text-xs tw-font-medium"
                                data-properties=\'' . json_encode($log->properties) . '\'>
                                Xem chi tiết
                            </button>';
                    }
                    return '<span class="tw-text-gray-400 tw-text-xs">---</span>';
                })
                ->rawColumns(['description', 'subject_id', 'ip_address', 'details'])
                ->make(true);
        }
    }

    public function getFilterData()
    {
        // Get log names
        $logNames = Activity::select('log_name')
            ->whereNotNull('log_name')
            ->where('log_name', '!=', '')
            ->distinct()
            ->pluck('log_name');

        $logNameData = $logNames->map(function ($name) {
            return [
                'id' => $name,
                'text' => $name
            ];
        })->values();

        // Get causers
        $causerIds = Activity::whereNotNull('causer_id')->distinct()->pluck('causer_id');

        $causerData = User::whereIn('id', $causerIds)
            ->select('id', 'name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name
                ];
            })->values();



        return response()->json([
            'logNameData' => $logNameData,
            'causerData'  => $causerData,
        ]);
    }
}
