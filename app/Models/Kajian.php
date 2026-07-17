<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kajian extends Model
{
    protected $table = 'kajian';

    protected $fillable = [
        'Tanggal',
        'WaktuSelesai',
        'Judul',
        'narasumber_id',
        'tempat_id',
        'kontak_id',
        'Informasi',
        'Tampilkan',
        'author',
        'last_modified_by',
    ];

    protected $casts = [
        'Tampilkan' => 'boolean'
    ];

    public function narasumber()
    {
        return $this->belongsTo(Narasumber::class, 'narasumber_id');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id');
    }

    public function kontak()
    {
        return $this->belongsTo(Kontak::class, 'kontak_id');
    }
}
