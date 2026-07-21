<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    protected $table = 'tempat';

    protected $fillable = [
        'nama',
        'deskripsi_alamat',
        'author',
        'last_modified_by',
    ];

    public function kajian()
    {
        return $this->hasMany(Kajian::class, 'tempat_id');
    }

    public function acara()
    {
        return $this->hasMany(Acara::class, 'tempat_id');
    }
}
