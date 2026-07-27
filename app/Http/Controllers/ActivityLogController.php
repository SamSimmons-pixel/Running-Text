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

    private function requireAdminOperator(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin_operator') {
            abort(403, 'Unauthorized!');
        }
    }

    public function index()
    {
        $this->requireAuth();

        $logs = ActivityLog::latest()->paginate(10);

        return view('activity_log', compact('logs'));
    }

    public function destroyAll()
    {
        $this->requireAdminOperator();

        ActivityLog::query()->delete();

        return redirect()->route('admin.activity-logs')
            ->with('success', 'Semua riwayat log aktivitas berhasil dibersihkan.');
    }

    public function destroy30()
    {
        $this->requireAdminOperator();

        $oldestIds = ActivityLog::orderBy('created_at', 'asc')->orderBy('id', 'asc')->limit(30)->pluck('id');

        if ($oldestIds->isNotEmpty()) {
            ActivityLog::whereIn('id', $oldestIds)->delete();
        }

        return redirect()->route('admin.activity-logs')
            ->with('success', '30 riwayat log aktivitas terlama berhasil dihapus.');
    }
}
