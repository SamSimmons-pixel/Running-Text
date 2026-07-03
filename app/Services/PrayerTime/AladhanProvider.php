<?php

namespace App\Services\PrayerTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AladhanProvider implements PrayerTimeProviderInterface
{
    public function getSchedule(string $city, Carbon $date): array
    {
        $dateStr = $date->format('d-m-Y');
        $url = "https://api.aladhan.com/v1/timingsByCity/{$dateStr}";

        Log::info("Fetching Aladhan prayer times for city: {$city}, date: {$dateStr}");

        $response = Http::timeout(5)
            ->get($url, [
                'city' => $city,
                'country' => 'Indonesia',
                'method' => 20, // Kemenag RI
            ]);

        if ($response->failed()) {
            throw new \Exception("Aladhan API request failed with status: " . $response->status());
        }

        $data = $response->json();

        if (($data['code'] ?? null) != 200 || !isset($data['data']['timings'])) {
            throw new \Exception("Invalid response payload from Aladhan API");
        }

        $timings = $data['data']['timings'];

        return [
            'subuh'   => $timings['Fajr'] ?? '',
            'dzuhur'  => $timings['Dhuhr'] ?? '',
            'ashar'   => $timings['Asr'] ?? '',
            'maghrib' => $timings['Maghrib'] ?? '',
            'isya'    => $timings['Isha'] ?? '',
        ];
    }
}
