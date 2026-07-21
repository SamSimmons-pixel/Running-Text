<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Narasumber extends Model
{
    protected $table = 'narasumber';

    protected $fillable = [
        'nama',
        'author',
        'last_modified_by',
    ];

    public function kajian()
    {
        return $this->hasMany(Kajian::class, 'narasumber_id');
    }

    public function acara()
    {
        return $this->hasMany(Acara::class, 'narasumber_id');
    }
}
