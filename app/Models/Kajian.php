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
        'author',
        'last_modified_by',
    ];

    protected $casts = [
        'Tampilkan'     => 'boolean'
    ];
}
