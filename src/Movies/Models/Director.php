<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table = 'directors';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function movies()
    {
        return $this->hasMany(Movie::class, 'directorId');
    }

    public static function searchDirectors($terms)
    {
        if (is_numeric($terms)) {
            $query = self::where('id', "like", "%$terms%");
        } else {
            $query = self::where('name', 'like', "%$terms%")->orWhere('bio', 'like', "%$terms%")->orWhere('birthDate', 'like', "%$terms%")->orWhere('deathDate', 'like', "%$terms%");
        }
        $results = $query->get();
        return $results;
    }
}
