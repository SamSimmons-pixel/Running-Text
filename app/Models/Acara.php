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
        'narasumber_id',
        'tempat_id',
        'status',
        'tampilkan',
        'author',
        'last_modified_by',
    ];

    protected $casts = [
        'tampilkan' => 'boolean',
    ];

    public function narasumber()
    {
        return $this->belongsTo(Narasumber::class, 'narasumber_id');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id');
    }
}
