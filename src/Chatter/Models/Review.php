<?php

namespace Chatter\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }
}