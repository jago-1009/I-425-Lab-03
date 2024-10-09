<?php
//require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chatter\Models\Movie;
use Chatter\Models\Review;

$app = new \Slim\App();

$app->get('/', function ($request, $response, $args) {
    $message = [
        "endpoints" => [
            "movies" => [
                "Get all movies" => "/movies",
                "Get a single movie" => "/movies/{id}",
            ]

        ]
    ];
    return $response->withJson($message);
});

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
$app->run();