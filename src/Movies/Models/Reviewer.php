<?php

namespace Movies\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
 protected $table = 'reviewer';
 protected $primaryKey = 'id';

 public $timestamps = false;

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewerId');
    }
}