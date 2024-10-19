<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieId');
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewerId');
    }
}