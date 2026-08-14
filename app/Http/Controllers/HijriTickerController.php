<?php

namespace App\Http\Controllers;

use App\Models\HijriSetting;
use App\Services\HijriService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HijriTickerController extends Controller
{
    protected $hijriService;

    public function __construct(HijriService $hijriService)
    {
        $this->hijriService = $hijriService;
    }

    private function requireOperator(): void
    {
        if (!\Illuminate\Support\Facades\Auth::check() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin_operator', 'operator'])) {
            abort(403, 'Unauthorized!');
        }
    }

    /**
     * Display the Hijri timing settings panel and live preview.
     */
    public function index()
    {
        $this->requireOperator();

        $settings = HijriSetting::firstOrCreate([], [
            'default_city' => 'Jakarta',
            'default_timezone' => 'Asia/Jakarta',
            'hijri_offset_days' => 0,
            'prayer_time_provider' => 'aladhan',
        ]);

        $cities = config('hijri.cities', []);

        // Load preview data
        $preview = $this->hijriService->getLiveTickerData();

        return view('pewaktuan_hijriah', compact('settings', 'cities', 'preview'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $this->requireOperator();

        $validated = $request->validate([
            'default_city' => 'required|string',
            'hijri_offset_days' => 'required|integer',
            'prayer_time_provider' => 'required|string|in:aladhan,myquran,alhabib',
            'show_masehi_suffix' => 'nullable|boolean',
            'show_hijri_suffix' => 'nullable|boolean',
        ]);

        $city = $validated['default_city'];
        $cities = config('hijri.cities', []);

        // Resolve timezone based on city
        $timezone = $cities[$city]['timezone'] ?? 'Asia/Jakarta';

        $settings = HijriSetting::firstOrCreate([]);

        $changes = [];
        if ($settings->default_city !== $city) {
            $changes[] = "kota dari \"{$settings->default_city}\" menjadi \"{$city}\"";
        }
        if ($settings->hijri_offset_days !== (int)$validated['hijri_offset_days']) {
            $changes[] = "offset hari dari {$settings->hijri_offset_days} menjadi {$validated['hijri_offset_days']}";
        }
        if ($settings->prayer_time_provider !== $validated['prayer_time_provider']) {
            $changes[] = "provider dari \"{$settings->prayer_time_provider}\" menjadi \"{$validated['prayer_time_provider']}\"";
        }
        $newMasehiSuffix = $request->has('show_masehi_suffix');
        if ($settings->show_masehi_suffix !== $newMasehiSuffix) {
            $statusStr = $newMasehiSuffix ? 'diaktifkan' : 'dinonaktifkan';
            $changes[] = "akhiran Masehi (M) {$statusStr}";
        }
        $newHijriSuffix = $request->has('show_hijri_suffix');
        if ($settings->show_hijri_suffix !== $newHijriSuffix) {
            $statusStr = $newHijriSuffix ? 'diaktifkan' : 'dinonaktifkan';
            $changes[] = "akhiran Hijriah (H) {$statusStr}";
        }

        $settings->update([
            'default_city' => $city,
            'default_timezone' => $timezone,
            'hijri_offset_days' => $validated['hijri_offset_days'],
            'prayer_time_provider' => $validated['prayer_time_provider'],
            'show_masehi_suffix' => $newMasehiSuffix,
            'show_hijri_suffix' => $newHijriSuffix,
        ]);

        if (count($changes) > 0) {
            $msg = \Illuminate\Support\Facades\Auth::user()->name . " mengubah pengaturan pewaktuan Hijriah: " . implode('; ', $changes);
            broadcast(new \App\Events\NotificationChange($msg));
        }

        // Force cache eviction for today's schedule to show updated timing immediately
        $dateKey = Carbon::now($timezone)->format('Y-m-d');
        Cache::forget("prayer-schedule-{$city}-{$dateKey}");

        return redirect()->back()->with('success', 'Pengaturan pewaktuan Hijriah berhasil diperbarui.');
    }
}
