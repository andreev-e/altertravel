<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoisComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'poi_id',
        'user_id',
        'comment',
        'email',
        'created_at',
        'updated_at',
        'status',

    ];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function poi()
    {

       return $this->belongsTo(Pois::class);
    }


}
