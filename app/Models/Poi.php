<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poi extends Model
{
    use HasFactory;

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
