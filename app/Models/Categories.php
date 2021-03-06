<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    public function pois()
    {
       return $this->hasMany(Pois::class, 'category_id');
    }

    protected $fillable = [
        'name',
        'url',
    ];

}
