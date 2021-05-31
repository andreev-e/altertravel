<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pois extends Model
{
    use HasFactory;

    public function locations()
    {
       return $this->belongsToMany(Locations::class, 'pois_locations')->orderBy('locations.id', 'asc');
    }
    public function tags()
    {
       return $this->belongsToMany(Tags::class);
    }

    public function comments()
    {
       return $this->hasMany(PoisComments::class, 'poi_id');
    }

    public function routes()
    {
       return $this->belongsToMany(Routes::class, 'routes_pois');
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function category()
    {
       return $this->belongsTo(Categories::class);
    }

    public function thumb()
    {
       if ($this->photo) $result=asset("/storage/pois/".$this->photo);
       else $result="-----";
       return $result;
    }

    public function main_image()
    {
       if ($this->photo) $result=asset("/storage/pois/".$this->photo);
       else $result="------";
       return $result;
    }

    public function gallery()
    {
      $this->photos=explode(",",$this->photos);
      foreach ($this->photos as $key => $value) {
        $this->photos[$key]=asset("/storage/pois/".$value);
      }
      return $this->photos;
    }

    protected $fillable = [
        'name',
        'url',
        'user_id',
        'category_id',
        'old_id',
        'status',
        'description',
        'route',
        'route_o',
        'prim',
        'video',
        'photos',
        'lat',
        'lng',
        'views',
        'dominatecolor',
        'copyright',
        'links'
    ];

}
