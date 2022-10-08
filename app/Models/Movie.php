<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable= [
        'imdb_id',
        'title',
        'poster',
        'release_year',
        'tag',
        'directors',
        'casts',
        'rent_period_from',
        'plan_type',
        'rent_period_to',
        'rent_price'
    ];

    public function rents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RentMovie::class,'movie_id','id');
    }
}
