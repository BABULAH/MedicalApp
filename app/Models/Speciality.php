<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $fillable = [
        'name',
        'description',
        'establishment_id',
    ];

    // Relation vers l'établissement
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    // Relation vers les médecins
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
