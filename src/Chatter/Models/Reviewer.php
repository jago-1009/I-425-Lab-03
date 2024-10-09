<?php

namespace Chatter\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
 protected $table = 'reviewers';
 protected $primaryKey = 'id';

 public $timestamps = false;


 public function reviews()
 {
     return $this->hasMany(Review::class,'review_id');
 }
}