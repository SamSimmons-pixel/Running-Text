<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acara extends Model
{
    protected $table = 'acara';

    protected $fillable = [
        'judul',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'narasumber',
        'tempat',
        'status',
        'tampilkan',
    ];

    protected $casts = [
        'tampilkan' => 'boolean',
    ];
}
