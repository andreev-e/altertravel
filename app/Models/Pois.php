<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pois extends Model
{
    use HasFactory;

    public function tags()
     {
         return $this->hasMany(Tags::class);
     }

    protected $fillable = [
        'name',
        'url',
        'owner',
        'status',
        'description',
        'category',
        'route',
        'prim',
        'video',
        'photos'
    ];

}
