<?php

namespace App\Services\PrayerTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlhabibProvider implements PrayerTimeProviderInterface
{
    public function getSchedule(string $city, Carbon $date): array
    {
        $path = config("hijri.cities.{$city}.alhabib_path");

        if (!$path) {
            throw new \Exception("Alhabib path not configured for city: {$city}");
        }

        $url = "https://www.al-habib.info/jadwal-shalat/di/{$path}";

        Log::info("Fetching Al-Habib prayer times from URL: {$url}");

        $response = Http::timeout(5)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ])
            ->get($url);

        if ($response->failed()) {
            throw new \Exception("Al-Habib request failed with status: " . $response->status());
        }

        $html = $response->body();

        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/si', $html, $matches);

        $times = [];
        foreach ($matches[1] as $tr_content) {
            if (preg_match('/<th[^>]*>(.*?)<\/th>.*?<time[^>]*>(.*?)<\/time>/si', $tr_content, $m)) {
                $name = trim(strip_tags($m[1]));
                $time = trim(strip_tags($m[2]));
                $times[$name] = $time;
            }
        }

        if (empty($times['Subuh']) || empty($times['Zuhur']) || empty($times['Maghrib'])) {
            throw new \Exception("Failed to parse prayer times from Al-Habib HTML");
        }

        return [
            'subuh'   => $times['Subuh'] ?? '',
            'dzuhur'  => $times['Zuhur'] ?? '',
            'ashar'   => $times['\'Asar'] ?? $times['Asar'] ?? '',
            'maghrib' => $times['Maghrib'] ?? '',
            'isya'    => $times['Isya\''] ?? $times['Isya'] ?? '',
        ];
    }
}
