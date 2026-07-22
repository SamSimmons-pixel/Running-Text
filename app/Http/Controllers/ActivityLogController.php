<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    private function requireAuth(): void
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized!');
        }
    }

    public function index()
    {
        $this->requireAuth();

        $logs = ActivityLog::latest()->paginate(10);

        return view('activity_log', compact('logs'));
    }
}
