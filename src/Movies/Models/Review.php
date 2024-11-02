<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['reviewerId', 'review', 'movieId', 'created_at', 'rating'];


    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieId');
    }
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewerId');
    }
}