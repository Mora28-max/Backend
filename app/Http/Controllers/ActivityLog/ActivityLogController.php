<?php

namespace App\Http\Controllers\ActivityLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use App\Http\Resources\ActivityLog\ActivityLogCollection;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        if (!auth()->user()->can('admin change roles'))
            return response(['message' => 'No tienes permiso para ver los registros de actividad.'], 403);

        $model = $request->search ?? '';
        $subject = $request->subject ?? '';
        $query = Activity::with('causer')->latest();

        if ($model) $query->where('log_name', $model);
        if ($subject) $query->where('subject_id', $subject);

        $logs = $query->paginate(15);
        return new ActivityLogCollection($logs);
    }
}
