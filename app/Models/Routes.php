<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function pois()
    {
      return $this->belongsToMany(Pois::class, 'routes_pois');
    }

    protected $fillable = [
        'name',
        'url',
        'user_id',
        'old_id',
        'status',
        'description',
        'route',
        'prim',
        'views',
        'duration',
        'links'
    ];
}
