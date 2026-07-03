<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HijriSetting extends Model
{
    protected $table = 'hijri_settings';

    protected $fillable = [
        'default_city',
        'default_timezone',
        'hijri_offset_days',
        'prayer_time_provider',
    ];
}
