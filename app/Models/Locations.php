<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    public function pois()
    {
       return $this->belongsToMany(Pois::class, 'pois_locations');
    }

    protected $fillable = [
        'name',
        'url',
        'parent',
        'type',
        'lat',
        'lng'

    ];
}
