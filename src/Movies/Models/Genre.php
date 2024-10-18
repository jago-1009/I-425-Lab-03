<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movies_genres', 'genreId', 'movieId');
    }


}
