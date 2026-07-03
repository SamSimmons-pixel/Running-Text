<?php

namespace App\Services\PrayerTime;

use Carbon\Carbon;

interface PrayerTimeProviderInterface
{
    /**
     * Get the prayer times schedule for a specific city and date.
     *
     * Should return a flat array containing:
     * - 'subuh' (format "HH:MM")
     * - 'dzuhur' (format "HH:MM")
     * - 'ashar' (format "HH:MM")
     * - 'maghrib' (format "HH:MM")
     * - 'isya' (format "HH:MM")
     *
     * @param string $city
     * @param Carbon $date
     * @return array
     * @throws \Exception
     */
    public function getSchedule(string $city, Carbon $date): array;
}
