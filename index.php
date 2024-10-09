<?php
//require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chatter\Models\Movie;

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
    $movie = Movie::where('id', '=', $args['id'])->first();
    $payload[$movie->id] = [
        'movieName' => $movie->movieName,
        'releaseDate' => $movie->releaseDate,
        'studioId' => $movie->studioId,
        'directorId' => $movie->directorId
    ];
    return $response->withJson($payload);

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
    return $response->withJson($movies);

});
$app->run();