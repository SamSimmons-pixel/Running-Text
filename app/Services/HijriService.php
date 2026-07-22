<?php

namespace App\Services;

use App\Models\HijriSetting;
use App\Services\PrayerTime\AladhanProvider;
use App\Services\PrayerTime\MyQuranProvider;
use App\Services\PrayerTime\AlhabibProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HijriService
{
    /**
     * Get the current prayer schedule and Hijri date.
     * Handles provider fallback, caching, and Maghrib rollover.
     *
     * @param string|null $timezoneOverride
     * @return array
     */
    public function getLiveTickerData(?string $timezoneOverride = null): array
    {
        // 1. Load active settings
        $settings = HijriSetting::firstOrCreate([], [
            'default_city' => 'Jakarta',
            'default_timezone' => 'Asia/Jakarta',
            'hijri_offset_days' => 0,
            'prayer_time_provider' => 'aladhan',
        ]);

        $city = $settings->default_city;
        $timezone = $timezoneOverride ?: $settings->default_timezone;
        $offsetDays = $settings->hijri_offset_days;
        $providerName = $settings->prayer_time_provider;
        $showMasehiSuffix = $settings->show_masehi_suffix ?? false;
        $showHijriSuffix = $settings->show_hijri_suffix ?? true;

        $now = Carbon::now($timezone)->locale('id');

        // 2. Instantiate Providers
        if ($providerName === 'alhabib') {
            $primary = new AlhabibProvider();
            $secondary = new AladhanProvider();
        } elseif ($providerName === 'myquran') {
            $primary = new MyQuranProvider();
            $secondary = new AladhanProvider();
        } else {
            $primary = new AladhanProvider();
            $secondary = new MyQuranProvider();
        }

        // 3. Fetch Prayer Schedule
        $schedule = $this->getPrayerScheduleCached($city, $now, $primary, $secondary);

        // 4. Calculate Hijri Date (Maghrib Rollover and Offset)
        $maghribTime = $schedule['maghrib'] ?? '18:00';
        if ($providerName === 'alhabib') {
            $hijriDate = $this->getAlhabibHijriDate($now, $maghribTime, $offsetDays, $showHijriSuffix);
        } else {
            $hijriDate = $this->formatToHijri($now, $maghribTime, $offsetDays, $timezone, $showHijriSuffix);
        }

        // Format Gregorian Date
        $masehiDate = $now->translatedFormat('j F Y') . ($showMasehiSuffix ? ' M' : '');

        return array_merge($schedule, [
            'tanggal_masehi' => $masehiDate,
            'tanggal_hijriah' => $hijriDate,
            'city' => $city,
            'timezone' => $timezone,
            'provider' => $providerName,
        ]);
    }

    /**
     * Get separate object list for Masehi and Hijriyah calendars.
     *
     * @param string|null $timezoneOverride
     * @return array
     */
    public function getCalendarObjects(?string $timezoneOverride = null): array
    {
        $settings = HijriSetting::firstOrCreate([], [
            'default_city' => 'Jakarta',
            'default_timezone' => 'Asia/Jakarta',
            'hijri_offset_days' => 0,
            'prayer_time_provider' => 'aladhan',
        ]);

        $city = $settings->default_city;
        $timezone = $timezoneOverride ?: $settings->default_timezone;
        $offsetDays = $settings->hijri_offset_days;
        $providerName = $settings->prayer_time_provider;

        $now = Carbon::now($timezone)->locale('id');

        if ($providerName === 'alhabib') {
            $primary = new AlhabibProvider();
            $secondary = new AladhanProvider();
        } elseif ($providerName === 'myquran') {
            $primary = new MyQuranProvider();
            $secondary = new AladhanProvider();
        } else {
            $primary = new AladhanProvider();
            $secondary = new MyQuranProvider();
        }

        $schedule = $this->getPrayerScheduleCached($city, $now, $primary, $secondary);
        $maghribTime = $schedule['maghrib'] ?? '18:00';

        $masehiObj = [
            'kalender' => 'Masehi',
            'tanggal'  => $now->translatedFormat('d'),
            'hari'     => $now->translatedFormat('l'),
            'bulan'    => $now->translatedFormat('F'),
            'tahun'    => $now->translatedFormat('Y'),
        ];

        if ($providerName === 'alhabib') {
            $hijriObj = $this->getAlhabibCalendarObject($now, $maghribTime, $offsetDays, $timezone);
        } else {
            $targetDate = $now->copy();
            $maghribParts = explode(':', $maghribTime);
            if (count($maghribParts) === 2) {
                $maghribCarbon = $targetDate->copy()->setTime((int)$maghribParts[0], (int)$maghribParts[1], 0);
                if ($targetDate->greaterThanOrEqualTo($maghribCarbon)) {
                    $targetDate->addDay();
                }
            }
            if ($offsetDays !== 0) {
                $targetDate->addDays($offsetDays);
            }

            $formatterDay = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'd'
            );
            $formatterMonth = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'MMMM'
            );
            $formatterYear = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'yyyy'
            );

            $hijriDayRaw = $formatterDay->format($targetDate->toDateTime());
            $hijriDay = is_numeric($hijriDayRaw) ? sprintf('%02d', (int)$hijriDayRaw) : $hijriDayRaw;

            $hijriObj = [
                'kalender' => 'Hijriyah',
                'tanggal'  => $hijriDay,
                'hari'     => $targetDate->translatedFormat('l'),
                'bulan'    => $formatterMonth->format($targetDate->toDateTime()),
                'tahun'    => $formatterYear->format($targetDate->toDateTime()),
            ];
        }

        return [$masehiObj, $hijriObj];
    }

    /**
     * Fetch prayer schedule with caching and fallback.
     */
    public function getPrayerScheduleCached(string $city, Carbon $date, $primary, $secondary): array
    {
        $dateKey = $date->format('Y-m-d');
        $cacheKey = "prayer-schedule-{$city}-{$dateKey}";
        $lastValidKey = "prayer-schedule-last-valid-{$city}";

        try {
            $schedule = Cache::remember($cacheKey, 86400, function() use ($primary, $secondary, $city, $date, $lastValidKey) {
                try {
                    $data = $primary->getSchedule($city, $date);
                } catch (\Exception $e) {
                    Log::warning("Primary prayer time provider failed: " . $e->getMessage() . ". Trying fallback provider.");
                    $data = $secondary->getSchedule($city, $date);
                }

                // Cache as last valid schedule for 30 days
                Cache::put($lastValidKey, $data, 86400 * 30);
                return $data;
            });
            $schedule['is_fallback'] = false;
        } catch (\Exception $e) {
            Log::error("All prayer time providers failed. Retrieving last valid cache for: {$city}. Error: " . $e->getMessage());
            $schedule = Cache::get($lastValidKey);
            if ($schedule) {
                $schedule['is_fallback'] = true;
            } else {
                // Absolute fallback dummy data
                $schedule = [
                    'subuh' => '04:30',
                    'dzuhur' => '12:00',
                    'ashar' => '15:15',
                    'maghrib' => '18:00',
                    'isya' => '19:15',
                    'is_fallback' => true,
                ];
            }
        }

        return $schedule;
    }

    /**
     * Formats Masehi datetime to Hijri with Maghrib rollover and manual offset.
     */
    public function formatToHijri(Carbon $date, string $maghribTime, int $offsetDays, string $timezone = 'Asia/Jakarta', ?bool $showHijriSuffix = null): string
    {
        if ($showHijriSuffix === null) {
            $settings = HijriSetting::first();
            $showHijriSuffix = $settings ? ($settings->show_hijri_suffix ?? true) : true;
        }

        $targetDate = $date->copy()->setTimezone($timezone);

        // Rollover logic: if now >= Maghrib, use tomorrow's Hijri date
        $maghribParts = explode(':', $maghribTime);
        if (count($maghribParts) === 2) {
            $maghribCarbon = $targetDate->copy()->setTime((int)$maghribParts[0], (int)$maghribParts[1], 0);
            if ($targetDate->greaterThanOrEqualTo($maghribCarbon)) {
                $targetDate->addDay();
            }
        }

        // Apply admin manual offset
        if ($offsetDays !== 0) {
            $targetDate->addDays($offsetDays);
        }

        // Format to Indonesian Hijri string using IntlDateFormatter
        $formatter = new \IntlDateFormatter(
            'id_ID@calendar=islamic-umalqura',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            $timezone,
            \IntlDateFormatter::TRADITIONAL,
            'd MMMM yyyy'
        );

        $hijriStr = trim($formatter->format($targetDate->toDateTime()));
        $hijriStr = preg_replace('/\s+H\.?$/i', '', $hijriStr);

        return $showHijriSuffix ? ($hijriStr . ' H') : $hijriStr;
    }

    /**
     * Fetch Hijri Date from Al-Habib API for a target date with Maghrib rollover and offset.
     */
    public function getAlhabibHijriDate(Carbon $date, string $maghribTime, int $offsetDays, ?bool $showHijriSuffix = null): string
    {
        if ($showHijriSuffix === null) {
            $settings = HijriSetting::first();
            $showHijriSuffix = $settings ? ($settings->show_hijri_suffix ?? true) : true;
        }

        $targetDate = $date->copy();

        // 1. Maghrib rollover logic
        $maghribParts = explode(':', $maghribTime);
        if (count($maghribParts) === 2) {
            $maghribCarbon = $targetDate->copy()->setTime((int)$maghribParts[0], (int)$maghribParts[1], 0);
            if ($targetDate->greaterThanOrEqualTo($maghribCarbon)) {
                $targetDate->addDay();
            }
        }

        // 2. Manual offset
        if ($offsetDays !== 0) {
            $targetDate->addDays($offsetDays);
        }

        $dateKey = $targetDate->format('Y-m-d');
        $cacheKey = "alhabib-hijri-date-{$dateKey}";

        try {
            $rawHijri = Cache::remember($cacheKey, 86400, function() use ($targetDate) {
                $query = http_build_query([
                    'the_y' => $targetDate->year,
                    'the_m' => $targetDate->month,
                    'the_d' => $targetDate->day,
                    'the_conv' => 'ctoh',
                    'lg' => 1
                ]);
                $url = "https://www.al-habib.info/utils/calendar/pengubah-kalender-hijriyah-v7.php?{$query}";

                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $json = $response->json();
                    if (!empty($json['tanggal_hijriyah'])) {
                        return trim($json['tanggal_hijriyah']);
                    }
                }
                throw new \Exception("Invalid response from Al-Habib calendar API");
            });

            $cleanHijri = preg_replace('/\s+H\.?$/i', '', $rawHijri);
            return $showHijriSuffix ? ($cleanHijri . ' H') : $cleanHijri;
        } catch (\Exception $e) {
            Log::warning("Failed to fetch Al-Habib Hijri date: " . $e->getMessage() . ". Falling back to local UmAlQura.");
            return $this->formatToHijri($date, $maghribTime, $offsetDays, $date->timezoneName, $showHijriSuffix);
        }
    }

    /**
     * Fetch calendar object from Al-Habib API
     */
    public function getAlhabibCalendarObject(Carbon $date, string $maghribTime, int $offsetDays, string $timezone): array
    {
        $targetDate = $date->copy()->setTimezone($timezone);

        $maghribParts = explode(':', $maghribTime);
        if (count($maghribParts) === 2) {
            $maghribCarbon = $targetDate->copy()->setTime((int)$maghribParts[0], (int)$maghribParts[1], 0);
            if ($targetDate->greaterThanOrEqualTo($maghribCarbon)) {
                $targetDate->addDay();
            }
        }

        if ($offsetDays !== 0) {
            $targetDate->addDays($offsetDays);
        }

        $dateKey = $targetDate->format('Y-m-d');
        $cacheKey = "alhabib-hijri-obj-{$dateKey}";

        try {
            $data = Cache::remember($cacheKey, 86400, function() use ($targetDate) {
                $query = http_build_query([
                    'the_y' => $targetDate->year,
                    'the_m' => $targetDate->month,
                    'the_d' => $targetDate->day,
                    'the_conv' => 'ctoh',
                    'lg' => 1
                ]);
                $url = "https://www.al-habib.info/utils/calendar/pengubah-kalender-hijriyah-v7.php?{$query}";

                $response = \Illuminate\Support\Facades\Http::timeout(5)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                    ])
                    ->get($url);

                if ($response->successful()) {
                    return $response->json();
                }
                throw new \Exception("Invalid response from Al-Habib calendar API");
            });

            return $data;

        } catch (\Exception $e) {
            Log::warning("Failed to fetch Al-Habib Hijri calendar object: " . $e->getMessage() . ". Falling back to local UmAlQura.");
            
            $formatterMonth = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'MMMM'
            );
            $formatterYear = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'yyyy'
            );
            $formatterDay = new \IntlDateFormatter(
                'id_ID@calendar=islamic-umalqura',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $timezone,
                \IntlDateFormatter::TRADITIONAL,
                'd'
            );

            $hijriDay = $formatterDay->format($targetDate->toDateTime());
            $hijriDay = preg_replace('/\D/', '', $hijriDay);

            return [
                'kalender' => 'Hijriyah',
                'tanggal'  => $hijriDay,
                'hari'     => $targetDate->translatedFormat('l'),
                'bulan'    => $formatterMonth->format($targetDate->toDateTime()),
                'tahun'    => $formatterYear->format($targetDate->toDateTime()),
            ];
        }
    }

    /**
     * Fast conversion of Gregorian Carbon date to Hijri string using 18:00 as default Maghrib rollover.
     */
    public function convertToHijriFast(Carbon $date, ?string $timezone = null): string
    {
        $settings = HijriSetting::first();
        $offsetDays = $settings ? $settings->hijri_offset_days : 0;
        $tz = $timezone ?: ($settings ? $settings->default_timezone : 'Asia/Jakarta');
        $providerName = $settings ? $settings->prayer_time_provider : 'aladhan';
        $showHijriSuffix = $settings ? ($settings->show_hijri_suffix ?? true) : true;

        if ($providerName === 'alhabib') {
            return $this->getAlhabibHijriDate($date, '18:00', $offsetDays, $showHijriSuffix);
        }

        return $this->formatToHijri($date, '18:00', $offsetDays, $tz, $showHijriSuffix);
    }

    /**
     * Fast conversion of Gregorian Carbon date to Masehi string with optional suffix.
     */
    public function formatMasehiFast(Carbon $date, ?bool $showMasehiSuffix = null): string
    {
        if ($showMasehiSuffix === null) {
            $settings = HijriSetting::first();
            $showMasehiSuffix = $settings ? ($settings->show_masehi_suffix ?? false) : false;
        }

        return $date->translatedFormat('d F Y') . ($showMasehiSuffix ? ' M' : '');
    }
}
