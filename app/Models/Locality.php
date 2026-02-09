<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = [
    'name',
    'region',
    'latitude',
    'longitude'
];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }
}
