<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiUmum extends Model
{
    protected $table = 'informasi_umum';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tampilkan',
        'author',
        'last_modified_by',
    ];

    protected $casts = [
        'tampilkan' => 'boolean',
    ];
}
