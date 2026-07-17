<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontak extends Model
{
    protected $table = 'kontak';

    protected $fillable = [
        'nama',
        'nomor_kontak',
        'author',
        'last_modified_by',
    ];

    public function kajian()
    {
        return $this->hasMany(Kajian::class, 'kontak_id');
    }
}
