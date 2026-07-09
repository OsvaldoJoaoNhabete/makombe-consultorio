<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = PatientActivityLog::with(['patient', 'user'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.activities.index', compact('activities'));
    }

    public function show($id)
    {
        $activity = PatientActivityLog::with(['patient', 'user'])->findOrFail($id);
        return view('admin.activities.show', compact('activity'));
    }

    public function clearOld()
    {
        PatientActivityLog::where('created_at', '<', now()->subDays(90))->delete();
        return back()->with('success', 'Logs antigos limpos.');
    }
}