<?php

namespace App\Services\PrayerTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyQuranProvider implements PrayerTimeProviderInterface
{
    public function getSchedule(string $city, Carbon $date): array
    {
        $cityId = config("hijri.cities.{$city}.myquran_id");

        if (!$cityId) {
            Log::info("City ID not configured for MyQuran, searching online for: {$city}");
            // Dynamic lookup search
            $searchResponse = Http::timeout(5)->get("https://api.myquran.com/v3/sholat/kabkota/cari/" . urlencode($city));
            if ($searchResponse->successful()) {
                $searchData = $searchResponse->json();
                if (($searchData['status'] ?? false) && !empty($searchData['data'])) {
                    // Grab the first matched city ID
                    $cityId = $searchData['data'][0]['id'] ?? null;
                }
            }
        }

        if (!$cityId) {
            throw new \Exception("City '{$city}' could not be resolved to an ID for MyQuran API");
        }

        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        $url = "https://api.myquran.com/v3/sholat/jadwal/{$cityId}/{$year}/{$month}/{$day}";

        Log::info("Fetching MyQuran prayer times from URL: {$url}");

        $response = Http::timeout(5)->get($url);

        if ($response->failed()) {
            throw new \Exception("MyQuran API request failed with status: " . $response->status());
        }

        $data = $response->json();

        if (!($data['status'] ?? false) || !isset($data['data']['jadwal'])) {
            throw new \Exception("Invalid response payload from MyQuran API");
        }

        $jadwal = $data['data']['jadwal'];

        return [
            'subuh'   => $jadwal['subuh'] ?? '',
            'dzuhur'  => $jadwal['dzuhur'] ?? '',
            'ashar'   => $jadwal['ashar'] ?? '',
            'maghrib' => $jadwal['maghrib'] ?? '',
            'isya'    => $jadwal['isya'] ?? '',
        ];
    }
}
