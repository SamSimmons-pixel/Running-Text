<?php

namespace App\Http\Controllers;

use App\Services\HijriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VmixDataController extends Controller
{
    protected $hijriService;

    public function __construct(HijriService $hijriService)
    {
        $this->hijriService = $hijriService;
    }

    /**
     * Endpoint for vMix Data Source. Returns flat JSON payload.
     */
    public function getTickerData(Request $request)
    {
        // 1. Resolve Timezone shorthand if provided
        $timezoneInput = $request->query('timezone');
        $resolvedTimezone = null;

        if ($timezoneInput) {
            $upper = strtoupper($timezoneInput);
            if ($upper === 'WIB') {
                $resolvedTimezone = 'Asia/Jakarta';
            } elseif ($upper === 'WITA') {
                $resolvedTimezone = 'Asia/Makassar';
            } elseif ($upper === 'WIT') {
                $resolvedTimezone = 'Asia/Jayapura';
            }
        }

        try {
            $data = $this->hijriService->getLiveTickerData($resolvedTimezone);

            // Return flat JSON response exactly matching the PRD structure
            return response()->json([
                'tanggal_masehi'  => $data['tanggal_masehi'] ?? '',
                'tanggal_hijriah' => $data['tanggal_hijriah'] ?? '',
                'subuh'           => $data['subuh'] ?? '',
                'dzuhur'          => $data['dzuhur'] ?? '',
                'ashar'           => $data['ashar'] ?? '',
                'maghrib'         => $data['maghrib'] ?? '',
                'isya'            => $data['isya'] ?? '',
            ]);
        } catch (\Exception $e) {
            Log::error("vMix endpoint error: " . $e->getMessage());

            // Never crash / return error, output placeholder values to prevent blank TV displays
            return response()->json([
                'tanggal_masehi'  => date('j F Y'),
                'tanggal_hijriah' => 'Error Load Data',
                'subuh'           => '--:--',
                'dzuhur'          => '--:--',
                'ashar'           => '--:--',
                'maghrib'         => '--:--',
                'isya'            => '--:--',
            ], 200);
        }
    }
}
