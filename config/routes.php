<?php

use Movies\Middleware\Logging as Logging;
use Movies\Authentication\Authenticator as Authenticator;

$app->get('/', function ($request, $response, $args) {
    $message = [
        "endpoints" => [
            "movies (Added pagination and sorting functionality along with search)" => [
                "Get all movies" => "/movies",
                "Get a single movie" => "/movies/{id}",
                "Get reviews for a specific movie by movie" => "/movies/{id}/reviews",
                "Add a new movie" => "/movies [POST]",
                "Update a movie" => "/movies/{id} [PATCH]",
                "Delete a movie" => "/movies/{id} [DELETE]",
            ],
            "directors (Added search functionality)" => [
                "Get all directors" => "/directors",
                "Get a single director" => "/directors/{id}",
                "Get all movies by a specific director" => "/directors/{id}/movies",
                "Add a new director" => "/directors [POST]",
                "Update a director" => "/directors/{id} [PATCH]",
                "Delete a director" => "/directors/{id} [DELETE]",
            ],
            "genres" => [
                "Get all genres" => "/genres",
                "Get a single genre" => "/genres/{id}",
                "Get all movies in a specific genre by genre" => "/genre/{id}/movies",
                "Add a new genre" => "/genres [POST]",
                "Update a genre" => "/genres/{id} [PATCH]",
                "Delete a genre" => "/genres/{id} [DELETE]",
            ],
            "studios" => [
                "Get all studios" => "/studios",
                "Get a single studio" => "/studios/{id}",
                "Get all movies by a specific studio" => "/studios/{id}/movies",
                "Add a new studio" => "/studios [POST]",
                "Update a studio" => "/studios/{id} [PATCH]",
                "Delete a studio" => "/studios/{id} [DELETE]",
            ],
            "reviews" => [
                "Get all reviews" => "/reviews",
                "Get a single review" => "/reviews/{id}",
                "Add a new review to a movie" => "/movies/{id}/reviews [POST]",
                "Update a review" => "/reviews/{id} [PATCH]",
                "Delete a review" => "/reviews/{id} [DELETE]",
            ],
            "reviewers" => [
                "Get all reviewers" => "/reviewers",
                "Get a single reviewer" => "/reviewers/{id}",
                "Get all reviews by a specific reviewer" => "/reviewers/{id}/reviews",
                "Add a new reviewer" => "/reviewers [POST]",
                "Update a reviewer" => "/reviewers/{id} [PATCH]",
                "Delete a reviewer" => "/reviewers/{id} [DELETE]",
            ],
        ]
    ];

    return $response->withJson($message);
});
$app->group('/movies', function ($app) {
    $app->get('', 'Movies\Controllers\MovieController:index');
    $app->get('/{id}', 'Movies\Controllers\MovieController:view');
    $app->get('/{id}/reviews', 'Movies\Controllers\MovieController:reviews');
    $app->post('', 'Movies\Controllers\MovieController:create');
    $app->patch('/{id}', 'Movies\Controllers\MovieController:update');
    $app->delete('/{id}', 'Movies\Controllers\MovieController:delete');
});

$app->group('/directors', function ($app) {
    $app->get('', 'Movies\Controllers\DirectorController:index');
    $app->get('/{id}', 'Movies\Controllers\DirectorController:view');
    $app->get('/{id}/movies', 'Movies\Controllers\DirectorController:viewMovie');
    $app->post('', 'Movies\Controllers\DirectorController:create');
    $app->patch('/{id}', 'Movies\Controllers\DirectorController:update');    
    $app->delete('/{id}', 'Movies\Controllers\DirectorController:delete');
});

$app->group('/genres', function ($app) {
    $app->get('', 'Movies\Controllers\GenreController:index');
    $app->get('/{id}', 'Movies\Controllers\GenreController:view');
    $app->get('/{id}/movies', 'Movies\Controllers\GenreController:viewMovie');
    $app->post('', 'Movies\Controllers\GenreController:create');
    $app->patch('/{id}', 'Movies\Controllers\GenreController:update');
    $app->delete('/{id}', 'Movies\Controllers\GenreController:delete');
});

$app->group('/studios', function ($app) {
    $app->get('', 'Movies\Controllers\StudioController:index');
    $app->get('/{id}', 'Movies\Controllers\StudioController:view');    
    $app->get('/{id}/movies', 'Movies\Controllers\StudioController:viewMovie');
    $app->post('', 'Movies\Controllers\StudioController:create');
    $app->patch('/{id}', 'Movies\Controllers\StudioController:update');    
    $app->delete('/{id}', 'Movies\Controllers\StudioController:delete');
});

$app->group('/reviews', function ($app) {
    $app->get('', 'Movies\Controllers\ReviewController:index');
    $app->get('/{id}', 'Movies\Controllers\ReviewController:view');    
    $app->post('/{id}', 'Movies\Controllers\ReviewController:create');
    $app->patch('/{id}', 'Movies\Controllers\ReviewController:update');
    $app->delete('/{id}', 'Movies\Controllers\ReviewController:delete');
});

$app->group('/reviewers', function ($app) {
    $app->get('', 'Movies\Controllers\ReviewerController:index');
    $app->get('/{id}', 'Movies\Controllers\ReviewerController:view');
    $app->get('/{id}/reviews', 'Movies\Controllers\ReviewerController:getReviews');
    $app->post('', 'Movies\Controllers\ReviewerController:create');
    $app->patch('/{id}', 'Movies\Controllers\ReviewerController:update');    
    $app->delete('/{id}', 'Movies\Controllers\ReviewerController:delete');
});
// $app->add(new Authenticator());
$app->add(new Logging());
$app->run();