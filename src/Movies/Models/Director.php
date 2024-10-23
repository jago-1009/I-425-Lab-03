<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table = 'directors';
    protected $primaryKey = 'id';

    //public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class, 'directorId');
    }
}
