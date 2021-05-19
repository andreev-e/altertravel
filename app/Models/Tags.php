<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
use HasFactory;

  public function pois()
  {
     return $this->belongsToMany(Pois::class, 'pois_tags');
  }


  protected $fillable = [
      'name',
      'url',
      'name_rod',
      'count',
      'old_id',
  ];


}
