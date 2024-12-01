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
    public static function getAllDirectors($params) {
        $term = isset($params['q']) ? $params['q'] : null;

        if (!is_null($term)) {
            $directors = Director::searchDirectors($term);
            $payload = [];
            foreach ($directors as $_director) {
                $payload[$_director->id] = [
                    'name'       => $_director->name,
                    'bio'        => $_director->bio,
                    'birthDate'  => $_director->birthDate,
                    'deathDate'  => $_director->deathDate,
                    'status' => 'successful'

                ];
            }
        } else {
            $directors = Director::all();
            $payload = [];
            foreach ($directors as $_director) {
                $payload[$_director->id] = [
                    'name'       => $_director->name,
                    'bio'        => $_director->bio,
                    'birthDate'  => $_director->birthDate,
                    'deathDate'  => $_director->deathDate,
                    'status' => 'successful'
                ];
            }
        }

        return $payload;
    }
    // Get a specific director by ID
    public static function getDirector($id) {
        $director = Director::find($id);
        $payload[$director->id] = [
            'name' => $director->name,
            'bio' => $director->bio,
            'birthDate' => $director->birthDate,
            'deathDate' => $director->deathDate,
            'status' => 'successful'
        ];
        return $payload;
    }
    // Get all movies by a specific director ID
    public static function getMoviesbyDirector($id) {
        $director = Director::find($id);
        $movies = $director->movies;
        $payload = [];
        foreach ($movies as $movie) {
            $payload[$movie->id] = [
                'director' => $director->name,
                'movieName' => $movie->movieName,
                'releaseDate' => $movie->releaseDate,
                'studioId' => $movie->studioId,
                'status' => 'successful'
            ];
        }
        return $payload;
    }
    public static function createDirector($request) {
        $director = new Director();
        $_name = $request->getParsedBodyParam('name', '');
        $_bio = $request->getParsedBodyParam('bio', '');
        $_birth_date = $request->getParsedBodyParam('birthDate', '');
        $_death_date = $request->getParsedBodyParam('deathDate', '');

        $director->name = $_name;
        $director->bio = $_bio;
        $director->birthDate = $_birth_date;
        $director->deathDate = $_death_date;

        $director->save();
        return [
            'status' => 'successful'
        ];

    }
    public static function updateDirector($director,$params) {
        foreach ($params as $field => $value) {
            $director->$field = $value;
        }

        $director->save();
        return $director;
    }
    public static function deleteDirector($id) {
        $director = Director::find($id);
        $director->delete();
        return [
            'status' => 'successful',
            "results" => "Director '/directors/$id' has been deleted."
            ];
    }
}
