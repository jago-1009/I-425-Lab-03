<?php
namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;
class Movie extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function reviews() {
        return $this->hasMany(Review::class, 'id');
    }



}