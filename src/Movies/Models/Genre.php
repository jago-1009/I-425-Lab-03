<?php

namespace Movies\Models;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movies_genres', 'genreId', 'movieId');
    }
    public static function getAllGenres() {
        $genres = self::all();
        $payload = [];
        foreach ($genres as $genre) {
            $payload[$genre->id] = [
                'name' => $genre->genreName,
                'description' => $genre->description,
                'status' => 'successful'
            ];
        }
        return $payload;
    }
    public static function getGenre($id) {
        $genre = Genre::find($id);
        $payload[$genre->id] = [
            'name' => $genre->genreName,
            'description' => $genre->description,
            'status' => 'successful'
        ];
        return $payload;
    }
    public static function getMoviesbyGenre($id) {
        $genre = Genre::find($id);
        $movies = $genre->movies;
        $payload = [];
        foreach ($movies as $movie) {
            $payload[] = [
                'genre' => $genre->genreName,
                'movieName' => $movie->movieName,
                'releaseDate' => $movie->releaseDate,
                'status' => 'successful'

            ];
        }
        return $payload;
    }
    public static function updateGenre($genre,$params) {
        foreach ($params as $field => $value) {
            $genre->$field = $value;
        }

        $genre->save();
        return [
            'status' => 'successful'
        ];
    }
    public static function deleteGenre($id) {
        $genre = Genre::find($id);
        $genre->delete();
        return [
            'status' => 'successful',
            "results" => "Genre '/genres/$id' has been deleted."
        ];
    }
}
