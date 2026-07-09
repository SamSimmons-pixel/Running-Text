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
                'Kalender Terpisah'        => $this->hijriService->getCalendarObjects($resolvedTimezone),
            ]);
        } catch (\Exception $e) {
            Log::error("vMix endpoint error: " . $e->getMessage());

            // Never crash / return error, output placeholder values to prevent blank TV displays
            return response()->json([
                'tanggal_masehi'    => \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y'),
                'tanggal_hijriah'   => 'Gagal Memuat Data',
                'subuh'             => '--:--',
                'dzuhur'            => '--:--',
                'ashar'             => '--:--',
                'maghrib'           => '--:--',
                'isya'              => '--:--',
                'Kalender Terpisah' => [],
            ], 200);
        }
    }

    /**
     * Combined endpoint for Informasi Umum, Kajian, and Acara.
     * Returns a flat JSON list with a single Ticker key.
     */
    public function tickerCombined()
    {
        try {
            // 1. Informasi Umum (only fetch where tampilkan = true)
            $informasiUmum = \App\Models\InformasiUmum::where('tampilkan', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    if ($item->deskripsi) {
                        return $item->deskripsi;
                    }
                    return $item->judul ?: $item->deskripsi;
                })
                ->filter()
                ->implode('       *       ');

            // 2. Informasi Kajian (only fetch where Tampilkan = true)
            $kajian = \App\Models\Kajian::where('Tampilkan', true)
                ->orderBy('Tanggal', 'asc')
                ->get()
                ->map(function ($item) {
                    $parts = [];
                    if ($item->Tanggal) {
                        $carbonDate = \Carbon\Carbon::parse($item->Tanggal)->locale('id');
                        $hari = $carbonDate->translatedFormat('l');
                        $hijriDate = $this->hijriService->convertToHijriFast($carbonDate);
                        $masehiDate = $carbonDate->translatedFormat('d F Y');
                        
                        $timeStr = $carbonDate->format('H:i');
                        if ($item->WaktuSelesai) {
                            $timeStr .= '-' . $item->WaktuSelesai;
                        }
                        
                        $parts[] = "{$hari}, {$hijriDate}, {$masehiDate} {$timeStr}";
                    }
                    
                    $details = [];
                    if ($item->Judul) {
                        $details[] = $item->Judul;
                    }
                    if ($item->Narasumber) {
                        $details[] = $item->Narasumber;
                    }
                    if ($item->Tempat) {
                        $details[] = '📍 ' . $item->Tempat;
                    }
                    
                    if (!empty($details)) {
                        $parts[] = implode(' - ', $details);
                    }
                    
                    return implode(' : ', $parts);
                })
                ->filter()
                ->implode('       *       ');

            if ($kajian !== '') {
                $kajian = 'Informasi Kajian : ' . $kajian;
            } else {
                $kajian = '';
            }

            // 3. Program Acara
            $acara = \App\Models\Acara::where('tampilkan', true)
                ->orderBy('hari', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->get()
                ->map(function ($item) {
                    $daysMap = [
                        'ahad'   => 'Sunday',
                        'minggu' => 'Sunday',
                        'senin'  => 'Monday',
                        'selasa' => 'Tuesday',
                        'rabu'   => 'Wednesday',
                        'kamis'  => 'Thursday',
                        'jumat'  => 'Friday',
                        'sabtu'  => 'Saturday',
                    ];

                    $carbonDate = \Carbon\Carbon::now('Asia/Jakarta')->locale('id');
                    $englishDay = $daysMap[strtolower($item->hari)] ?? null;
                    if ($englishDay) {
                        $todayEnglish = $carbonDate->locale('en')->isoFormat('dddd');
                        if (strtolower($todayEnglish) !== strtolower($englishDay)) {
                            $carbonDate->next($englishDay);
                        }
                    }

                    if ($item->jam_mulai) {
                        $timeParts = explode(':', $item->jam_mulai);
                        if (count($timeParts) >= 2) {
                            $carbonDate->setTime((int)$timeParts[0], (int)$timeParts[1], isset($timeParts[2]) ? (int)$timeParts[2] : 0);
                        }
                    }

                    $hari = $carbonDate->translatedFormat('l');
                    $hijriDate = $this->hijriService->convertToHijriFast($carbonDate);
                    $masehiDate = $carbonDate->translatedFormat('d F Y');

                    $timeStr = '';
                    if ($item->jam_mulai) {
                        $jamMulai = date('H:i', strtotime($item->jam_mulai));
                        $timeStr .= $jamMulai;
                        if ($item->jam_selesai) {
                            $jamSelesai = date('H:i', strtotime($item->jam_selesai));
                            $timeStr .= '-' . $jamSelesai;
                        }
                    }

                    $parts = ["{$hari}, {$hijriDate}, {$masehiDate} {$timeStr}"];
                    
                    $details = [];
                    if ($item->judul) {
                        $details[] = $item->judul;
                    }
                    if ($item->narasumber) {
                        $details[] = $item->narasumber;
                    }
                    if ($item->tempat) {
                        $details[] = '📍 ' . $item->tempat;
                    }
                    
                    if (!empty($details)) {
                        $parts[] = implode(' - ', $details);
                    }
                    
                    return implode(' : ', $parts);
                })
                ->filter()
                ->implode('       *       ');

            if ($acara !== '') {
                $acara = 'Informasi Acara TV : ' . $acara;
            } else {
                $acara = '';
            }

            $sections = [
                $informasiUmum,
                $kajian,
                $acara,
            ];

            

            // Drop any section that came back empty, THEN join
            $tickerText = collect($sections)
                ->filter(fn ($s) => $s !== '')
                ->implode('          |          ');

            return response()->json([
                [
                    'Ticker' => $tickerText,
                    'Informasi' => $informasiUmum,
                    'Kajian' => $kajian,
                    'Acara' => $acara,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("vMix combined ticker endpoint error: " . $e->getMessage());

            return response()->json([
                [
                    'Ticker' => 'Gagal Memuat Data',
                    'Informasi' => 'Gagal Memuat Data',
                    'Kajian' => 'Gagal Memuat Data',
                    'Acara' => 'Gagal Memuat Data',
                ]
            ], 200);
        }
    }
}
