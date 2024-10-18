<?php
//require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chatter\Models\Movie;
use Chatter\Models\Review;
use Chatter\Models\Director;
use Chatter\Models\Genre;
use Chatter\Models\Studio;

$app = new \Slim\App();

$app->get('/', function ($request, $response, $args) {
    $message = [
        "endpoints" => [
            "movies" => [
                "Get all movies" => "/movies",
                "Get a single movie" => "/movies/{id}",
            ],
            "directors" => [
                "Get all directors" => "/directors",
                "Get a single director" => "/directors/{id}",
            ],
            "genres" => [
                "Get all genres" => "/genres",
                "Get a single genre" => "/genres/{id}",
            ],
            "studios" => [
                "Get all studios" => "/studios",
                "Get a single studio" => "/studios/{id}"
            ],
        ]
    ];
    return $response->withJson($message);
});

// GET MOVIES FUNCTIONS

// Get a specific movie by ID
$app->get('/movies/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $movie = Movie::find($id);
    $payload[$movie->id] = [
        'movieName' => $movie->movieName,
        'releaseDate' => $movie->releaseDate,
        'studioId' => $movie->studioId,
        'directorId' => $movie->directorId
    ];
    return $response->withStatus(200)->withJson($payload);
});

// Get all movies
$app->get('/movies', function ($request, $response, $args) {
    $movies = Movie::all();
    $payload = [];
    foreach ($movies as $movie) {
        $payload[$movie->id] = [
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate,
            'studioId' => $movie->studioId,
            'directorId' => $movie->directorId
        ];
    }
    return $response->withStatus(200)->withJson($movies);
});

// Get reviews for a specific movie by movie ID
$app->get('/movies/{id}/reviews', function ($request, $response, $args) {
    $id = $args['id'];
    $movie = new Movie();
    $reviews = $movie->find($id)->reviews;
    $payload = [];
    foreach ($reviews as $review) {
        $payload[$review->id] = [
            "reviewer_id" => $review->reviewerId,
            "review"=>$review->review,
            "movie_id" => $review->movieId,
            "created_at" => $review->created_at,
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// GET DIRECTORS FUNCTIONS

// Get all directors
$app->get('/directors', function ($request, $response, $args) {
    $directors = Director::all();
    $payload = [];
    foreach ($directors as $director) {
        $payload[$director->id] = [
            'name' => $director->name,
            'bio' => $director->bio,
            'birthDate' => $director->birthDate,
            'deathDate' => $director->deathDate,
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// Get a specific director by ID
$app->get('/directors/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $director = Director::find($id);
    $payload[$director->id] = [
        'name' => $director->name,
        'bio' => $director->bio,
        'birthDate' => $director->birthDate,
        'deathDate' => $director->deathDate
    ];
    return $response->withStatus(200)->withJson($payload);
});

// Get all movies by a specific director ID
$app->get('/directors/{id}/movies', function ($request, $response, $args) {
    $id = $args['id'];
    $director = Director::find($id);
    $movies = $director->movies;
    $payload = [];
    foreach ($movies as $movie) {
        $payload[$movie->id] = [
            'director' => $director->name,
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate,
            'studioId' => $movie->studioId,
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// GET GENRES FUNCTIONS

// Get all genres
$app->get('/genres', function ($request, $response, $args) {
    $genres = Genre::all();
    $payload = [];
    foreach ($genres as $genre) {
        $payload[$genre->id] = [
            'name' => $genre->genreName,
            'description' => $genre->description,
        ];
    }
    return $response->withStatus(200)->withJson($genres);
});

// Get a specific genre by ID
$app->get('/genres/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $genre = Genre::find($id);
    $payload[$genre->id] = [
        'name' => $genre->genreName,
        'description' => $genre->description,
    ];
    return $response->withStatus(200)->withJson($payload);
});

// Get all movies in a specific genre by genre ID
$app->get('/genres/{id}/movies', function ($request, $response, $args) {
    $id = $args['id'];
    $genre = Genre::find($id);
    $movies = $genre->movies;
    $payload = [];
    foreach ($movies as $movie) {
        $payload[] = [
            'genre' => $genre->genreName,
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// GET STUDIOS FUNCTIONS

// Get all studios
$app->get('/studios', function ($request, $response, $args) {
    $studios = Studio::all();
    $payload = [];
    foreach ($studios as $studio) {
        $payload[$studio->id] = [
            'name' => $studio->name,
            'description' => $studio->description,
            'founding date' => $studio->foundingDate,
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// Get a specific studio by ID
$app->get('/studios/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $studios = Studio::find($id);
    $payload[$studios->id] = [
        'name' => $studios->name,
        'description' => $studios->description,
        'founding date' => $studios->foundingDate
    ];
    return $response->withStatus(200)->withJson($payload);
});

// Get all movies by a specific studio ID
$app->get('/studios/{id}/movies', function ($request, $response, $args) {
    $id = $args['id'];
    $studio = Studio::find($id);
    $movies = $studio->movies;
    $payload = [];
    foreach ($movies as $movie) {
        $payload[$movie->id] = [
            'studio' => $studio->name,
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate,
            'directorId' => $movie->directorId
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});


$app->run();