<?php

namespace Tests\Unit;

use App\Services\HijriService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class HijriServiceTest extends TestCase
{
    /**
     * Test Hijri calendar formatting output.
     */
    public function test_hijri_formatting(): void
    {
        $service = new HijriService();

        // 3 July 2026 is 18 Muharram 1448
        $gregorianDate = Carbon::create(2026, 7, 3, 12, 0, 0, 'Asia/Jakarta');

        // 12:00 is before Maghrib (e.g. 17:48), so it should not roll over
        $hijriDate = $service->formatToHijri($gregorianDate, '17:48', 0, 'Asia/Jakarta');

        $this->assertStringContainsString('18', $hijriDate);
        $this->assertStringContainsString('Muhar', $hijriDate); // Matches Muharam or Muharram
        $this->assertStringContainsString('1448', $hijriDate);
    }

    /**
     * Test Maghrib rollover logic.
     */
    public function test_maghrib_rollover(): void
    {
        $service = new HijriService();

        // Target Date: 3 July 2026 (base: 18 Muharram 1448)

        // Scenario 1: Time is 17:00 (before Maghrib 17:48) -> should remain 18 Muharram
        $beforeMaghrib = Carbon::create(2026, 7, 3, 17, 0, 0, 'Asia/Jakarta');
        $hijriBefore = $service->formatToHijri($beforeMaghrib, '17:48', 0, 'Asia/Jakarta');
        $this->assertStringContainsString('18', $hijriBefore);

        // Scenario 2: Time is 18:00 (after Maghrib 17:48) -> should rollover to 19 Muharram
        $afterMaghrib = Carbon::create(2026, 7, 3, 18, 0, 0, 'Asia/Jakarta');
        $hijriAfter = $service->formatToHijri($afterMaghrib, '17:48', 0, 'Asia/Jakarta');
        $this->assertStringContainsString('19', $hijriAfter);
    }

    /**
     * Test manual offset adjustment.
     */
    public function test_manual_offset(): void
    {
        $service = new HijriService();

        // Target Date: 3 July 2026 (12:00 - no rollover). Base: 18 Muharram 1448
        $gregorianDate = Carbon::create(2026, 7, 3, 12, 0, 0, 'Asia/Jakarta');

        // Scenario 1: Offset -1 -> should output 17 Muharram
        $hijriMinus = $service->formatToHijri($gregorianDate, '17:48', -1, 'Asia/Jakarta');
        $this->assertStringContainsString('17', $hijriMinus);

        // Scenario 2: Offset +1 -> should output 19 Muharram
        $hijriPlus = $service->formatToHijri($gregorianDate, '17:48', 1, 'Asia/Jakarta');
        $this->assertStringContainsString('19', $hijriPlus);
    }
}
