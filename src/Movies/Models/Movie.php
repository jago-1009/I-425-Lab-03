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

    public static function getLinks($request, $limit, $offset){
        $count = self::count();

        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();

        $links = array();

        $links[] = [
            'rel' => 'self',
            'href' => $base_url . "/$path" . "?limit=$limit&offset=$offset"
        ];
        $links[] = [
            'rel' => 'first',
            'href' => $base_url . "/$path" . "?limit=$limit&offset=0"
        ];

        if ($offset - $limit >= 0){
            $links[] = [
                'rel' => 'prev',
                'href' => $base_url . "/$path" . "?limit=$limit&offset=" . ($offset-$limit)
            ];
        }

        if ($offset + $limit < $count){
            $links[] = [
                'rel' => 'next',
                'href' => $base_url . "/$path" . "?limit=$limit&offset=" . ($offset+$limit)
            ];
        }

        $links[] = [
            'rel' => 'last',
            'href' => $base_url . "/$path" . "?limit=$limit&offset=" . $limit * (ceil($count/$limit) - 1)
        ];

        return $links;
    }

    public static function getSortKeys($request)
    {
        $sort_key_array = array();

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']); // remove white spaces, [, and ]

            $sort_keys = explode(',', $sort); // get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;

                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }

                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }

    public static function getAllMovies() {
        $movies = self::all();
        $payload = [];
        foreach ($movies as $movie) {
            $payload[$movie->id] = [
                'movieName' => $movie->movieName,
                'releaseDate' => $movie->releaseDate,
                'studioId' => $movie->studioId,
                'directorId' => $movie->directorId,
                'status' => 'successful'
            ];
        }
        return $payload;
    }
    public static function getMovie($id) {
        $movie = self::find($id);
        $payload[$movie->id] = [
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate,
            'studioId' => $movie->studioId,
            'directorId' => $movie->directorId,
            'status' => 'successful'
        ];
        return $payload;
    }
    public static function searchMovies($terms)
    {
        if (is_numeric($terms)) {
            $query = self::where('id', "like", "%$terms%");
        } else {
            $query = self::where('movieName', 'like', "%$terms%")->orWhere('releaseDate', 'like', "%$terms%");
        }
        $results = $query->get();
        return $results;
    }
    public static function createMovie($request) {
        $movie = new Movie();
        $movie->movieName = $request->getParsedBodyParam('movieName','');
        $movie->releaseDate = $request->getParsedBodyParam('releaseDate','');
        $movie->studioId = $request->getParsedBodyParam('studioId','');
        $movie -> directorId = $request->getParsedBodyParam('directorId','');

        $movie->save();
        return [
            'status' => 'successful'
        ];
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
    public static function updateMovie($movie,$params) {
        foreach ($params as $field => $value) {
            $movie->$field = $value;
        }
        $movie->save();
        return [
            'status' => 'successful'
        ];
    }
    public static function deleteMovie($id) {
        $movie = self::find($id);
        $movie->delete();
        return [
            'status' => 'successful',
            "results" => "Movie '/movies/$id' has been deleted."
        ];
    }
}