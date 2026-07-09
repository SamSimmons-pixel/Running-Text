<?php

namespace App\Services;

use App\Models\HijriSetting;
use App\Services\PrayerTime\AladhanProvider;
use App\Services\PrayerTime\MyQuranProvider;
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

        $now = Carbon::now($timezone)->locale('id');

        // 2. Instantiate Providers
        $primary = $providerName === 'myquran' ? new MyQuranProvider() : new AladhanProvider();
        $secondary = $providerName === 'myquran' ? new AladhanProvider() : new MyQuranProvider();

        // 3. Fetch Prayer Schedule
        $schedule = $this->getPrayerScheduleCached($city, $now, $primary, $secondary);

        // 4. Calculate Hijri Date (Maghrib Rollover and Offset)
        $maghribTime = $schedule['maghrib'] ?? '18:00';
        $hijriDate = $this->formatToHijri($now, $maghribTime, $offsetDays, $timezone);

        // Format Gregorian Date
        $masehiDate = $now->translatedFormat('j F Y');

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

        $primary = $providerName === 'myquran' ? new MyQuranProvider() : new AladhanProvider();
        $secondary = $providerName === 'myquran' ? new AladhanProvider() : new MyQuranProvider();
        $schedule = $this->getPrayerScheduleCached($city, $now, $primary, $secondary);
        $maghribTime = $schedule['maghrib'] ?? '18:00';

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

        $masehiObj = [
            'kalender' => 'Masehi',
            'tanggal'  => $now->translatedFormat('d'),
            'hari'     => $now->translatedFormat('l'),
            'bulan'    => $now->translatedFormat('F'),
            'tahun'    => $now->translatedFormat('Y'),
        ];

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
    public function formatToHijri(Carbon $date, string $maghribTime, int $offsetDays, string $timezone = 'Asia/Jakarta'): string
    {
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

        return $formatter->format($targetDate->toDateTime());
    }

    /**
     * Fast conversion of Gregorian Carbon date to Hijri string using 18:00 as default Maghrib rollover.
     */
    public function convertToHijriFast(Carbon $date, ?string $timezone = null): string
    {
        $settings = HijriSetting::first();
        $offsetDays = $settings ? $settings->hijri_offset_days : 0;
        $tz = $timezone ?: ($settings ? $settings->default_timezone : 'Asia/Jakarta');

        return $this->formatToHijri($date, '18:00', $offsetDays, $tz);
    }
}
