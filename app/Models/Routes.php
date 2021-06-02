<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Intervention\Image\ImageManagerStatic as Image;

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

    public function comments()
    {
      return $this->hasMany(RoutesComments::class, 'routes_id');
    }

    public function thumb()
    {
       if ($this->photo and 0) {
         if (!file_exists(storage_path().'/app/public/routes_thumbs/'.$this->photo)) {
         $image_resize = Image::make(storage_path().'/app/public/routes/'.$this->photo);
         if (!file_exists(storage_path().'/app/public/routes_thumbs/'.$this->id)) mkdir(storage_path().'/app/public/routes_thumbs/'.$this->id, 0755, true);
         $image_resize->resize(300, null, function ($constraint) {$constraint->aspectRatio();})->crop(300, 200)->save(storage_path().'/app/public/routes_thumbs/'.$this->photo);
          }
         $result=asset("/storage/routes_thumbs/".$this->photo);

       }
       else $result="/i/empty.jpg";

       return $result;
    }

    public function main_image()
    {
      if ($this->photo) $result="/storage/".$this->photo;
      else if ($this->pois->count()>0) $result=$result=$this->pois->first()->main_image();
      else  $result="https://altertravel.ru/routes/".$this->old_id."/1.jpg";
      return $result;
    }

    public function gallery()
    {
      return explode(",",$this->photos);
    }



    protected $fillable = [
        'name',
        'url',
        'user_id',
        'old_id',
        'status',
        'description',
        'route',
        'photo',
        'photos',
        'prim',
        'views',
        'duration',
        'links'
    ];
}
