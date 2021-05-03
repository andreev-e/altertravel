<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{

  public function pois()
  {
    return $this->belongsToMany(Pois::class, 'pois_to_tags');
  }


    use HasFactory;
}
