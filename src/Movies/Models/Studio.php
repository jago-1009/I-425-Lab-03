<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $table = 'studios';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class, 'studioId');
    }
}
