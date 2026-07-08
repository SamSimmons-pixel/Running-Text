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
        'Judul',
        'Narasumber',
        'Tempat',
        'Kontak',
        'Tampilkan',
    ];

    protected $casts = [
        'Tampilkan'     => 'boolean'
    ];
}
