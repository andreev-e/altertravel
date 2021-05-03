<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquement\SoftDeletes;

class Pois extends Model
{
    use HasFactory;

    public function locations()
    {
       return $this->belongsToMany(Locations::class, 'pois_locations');
    }
    public function tags()
    {
       return $this->belongsToMany(Tags::class);
    }
    public function user()
    {
       return $this->belongsTo(User::class);
    }


    protected $fillable = [
        'name',
        'url',
        'user_id',
        'status',
        'description',
        'category',
        'route',
        'prim',
        'video',
        'photos'
    ];

}
