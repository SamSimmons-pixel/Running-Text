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
        $settings->update([
            'default_city' => $city,
            'default_timezone' => $timezone,
            'hijri_offset_days' => $validated['hijri_offset_days'],
            'prayer_time_provider' => $validated['prayer_time_provider'],
            'show_masehi_suffix' => $request->has('show_masehi_suffix'),
            'show_hijri_suffix' => $request->has('show_hijri_suffix'),
        ]);

        // Force cache eviction for today's schedule to show updated timing immediately
        $dateKey = Carbon::now($timezone)->format('Y-m-d');
        Cache::forget("prayer-schedule-{$city}-{$dateKey}");

        return redirect()->back()->with('success', 'Pengaturan pewaktuan Hijriah berhasil diperbarui.');
    }
}
