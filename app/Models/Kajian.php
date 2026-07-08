<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kajian extends Model
{
    protected $table = 'kajian';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'Tanggal',
        'WaktuSelesai',
        'Judul',
        'Narasumber',
        'Tempat',
        'Kontak',
        'Informasi',
        'Tampilkan',
    ];

    protected $casts = [
        'Tampilkan'     => 'boolean'
    ];
}
