<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Intervention\Image\ImageManagerStatic as Image;

class Pois extends Model
{
    use HasFactory;

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

    public function locations()
    {
       return $this->belongsToMany(Locations::class, 'pois_locations')->orderBy('locations.id', 'asc');
    }

    public function photoes()
    {
       return $this->belongsToMany(Photoes::class);
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
       if ($this->photo) {
         if (!file_exists(storage_path().'/app/public/pois_thumbs/'.$this->photo)) {
         $image_resize = Image::make(storage_path().'/app/public/pois/'.$this->photo);
         if (!file_exists(storage_path().'/app/public/pois_thumbs/'.$this->id)) mkdir(storage_path().'/app/public/pois_thumbs/'.$this->id, 0755, true);
         $image_resize->resize(300, null, function ($constraint) {$constraint->aspectRatio();})->crop(300, 200)->save(storage_path().'/app/public/pois_thumbs/'.$this->photo);
          }
         $result=asset("/storage/pois_thumbs/".$this->photo);

       }
       else $result="/i/empty.jpg";

       return $result;
    }

    public function main_image()
    {
       if ($this->photo) $result=asset("/storage/pois/".$this->photo);
       else $result="/i/empty.jpg";
       return $result;
    }

    public function gallery()
    {
        $photos=explode(",",$this->photos);
        foreach ($photos as $key => $value) {
        $photos[$key]=asset("/storage/pois/".$value);
      }
      return $photos;
    }



}
