<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

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
       if ($this->photo) $result=asset("/storage/".$this->photo);
       elseif ($this->pois->count()>0) $result=$this->pois->first()->thumb();
       else $result="https://altertravel.ru/thumb.php?f=/routes/".$this->old_id."/1.jpg";
       return $result;
    }

    public function main_image()
    {
      if ($this->photo) $result="/".$this->photo;
      else if ($this->pois->count()>0) $result=$result=$this->pois->first()->main_image();
      else  $result="https://altertravel.ru/routes/".$this->old_id."/1.jpg";
      return $result;
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
