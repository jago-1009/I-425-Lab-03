<?php
//require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Movie;
use Movies\Models\Review;
use Movies\Models\Director;
use Movies\Models\Genre;
use Movies\Models\Studio;
use Movies\Models\Reviewer;
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,  // Display detailed errors
        'logErrors' => true,
        'logErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

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

// GET MOVIES FUNCTIONS

// Get all movies (Added pagination and search)
$app->get('/movies', function ($request, $response, $args) {
    $count = Movie::count();
    $params = $request->getQueryParams();

    $limit  = isset($params['limit']) ? (int)$params['limit'] : 3;
    $offset = isset($params['offset']) ? (int)$params['offset'] : 0;

    $term = isset($params['q']) ? $params['q'] : null;

    if (!is_null($term)) {
        $movies = Movie::searchMovies($term);
        $payload_final = [];
        foreach ($movies as $_movie) {
            $payload_final[$_movie->id] = [
                'movieName'   => $_movie->movieName,
                'releaseDate' => $_movie->releaseDate,
                'studioId'    => $_movie->studioId,
                'directorId'  => $_movie->directorId
            ];
        }
    } else {
        $links = Movie::getLinks($request, $limit, $offset);

        $sort_key_array = Movie::getSortKeys($request);

        $query = Movie::skip($offset)->take($limit);

        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $movies = $query->get();

        $payload = [];
        foreach ($movies as $_movie) {
            $payload[$_movie->id] = [
                'movieName'   => $_movie->movieName,
                'releaseDate' => $_movie->releaseDate,
                'studioId'    => $_movie->studioId,
                'directorId'  => $_movie->directorId
            ];
        }

        $payload_final = [
            'totalCount' => $count,
            'limit'      => $limit,
            'offset'     => $offset,
            'links'      => $links,
            'sort'       => $sort_key_array,
            'data'       => $payload
        ];
    }

    return $response->withStatus(200)->withJson($payload_final);
});


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

// GET REVIEWS

// Get all reviews
$app->get('/reviews', function ($request, $response, $args) {
    $reviews = Review::all();
    $payload = [];
    foreach ($reviews as $review) {
        $payload[$review->id] = [
            'review' => $review->review,
            'movieId' => $review->movieId,
            'created_at' => $review->created_at,
            'reviewerId' => $review->reviewerId,
            'rating' => $review->rating,
        ];
    }
    return $response->withStatus(200)->withJson($reviews);
});

// Get a specific review by ID
$app->get('/reviews/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $review = Review::find($id);
    $payload[$review->id] = [
        'review' => $review->review,
        'movieId' => $review->movieId,
        'created_at' => $review->created_at,
        'reviewerId' => $review->reviewerId,
        'rating' => $review->rating,
    ];
    return $response->withStatus(200)->withJson($payload);
});

// GET REVIEWERS

// Get all reviewers
$app->get('/reviewers', function ($request, $response, $args) {
    $reviewers = Reviewer::all();
    $payload = [];
    foreach ($reviewers as $reviewer) {
        $payload[$reviewer->id] = [
            'id' => $reviewer->id,
            'name' => $reviewer->name,
            'created_at' => $reviewer->created_at,
        ];
    }
    return $response->withStatus(200)->withJson($payload);
});

// Get a specific reviewer by ID
$app->get('/reviewers/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $reviewer = Reviewer::find($id);
    $payload[$reviewer->id] = [
        'id' => $reviewer->id,
        'name' => $reviewer->name,
        'created_at' => $reviewer->created_at,
    ];
    return $response->withStatus(200)->withJson($payload);
});

// Get all reviews by specific reviewer
$app->get('/reviewers/{id}/reviews', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $reviewer = Reviewer::find($id);

    $reviews = $reviewer->reviews;

    $payload = [];

    foreach ($reviews as $review) {
        $payload[$review->id] = [
            'id' => $review->id,
            'name' => $reviewer->name,
            'review' => $review->review,
            'movieId' => $review->movieId,
            'created_at' => $review->created_at,
            'reviewerId' => $review->reviewerId,
            'rating' => $review->rating,
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


// POST METHODS
$app->post('/movies', function (Request $request, Response $response, $args) {
    $movie = new Movie();
    $_movie_name = $request->getParsedBodyParam('movieName','');
    $_release_date = $request->getParsedBodyParam('releaseDate','');
    $_studio_id = $request->getParsedBodyParam('studioId','');
    $_director_id = $request->getParsedBodyParam('directorId','');
    $movie->movieName = $_movie_name;
    $movie->releaseDate = $_release_date;
    $movie->studioId = $_studio_id;
    $movie -> directorId = $_director_id;
    $movie->save();
    if ($movie->id) {
        $payload = ['movieId' => $movie->id,
            'movie_uri' => '/movies/' . $movie->id
        ];
        return $response->withStatus(201)->withJson($payload);
    }
    else {

        return $response->withStatus(500);
    }
});


$app->post('/genres', function (Request $request, Response $response, $args) {
    $genre = new Genre();
    $_genre_name = $request->getParsedBodyParam('genreName', '');
    $_description = $request->getParsedBodyParam('description', '');


    $genre->genreName=$_genre_name;
    $genre->description = $_description;

    $genre->save();

    if ($genre->id) {
        $payload = [
            'genreId' => $genre->id,
            'genre_uri' => '/genres/' . $genre->id
        ];
        return $response->withStatus(201)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->post('/reviewers', function (Request $request, Response $response, $args) {
    $reviewer = new Reviewer();
    $_name = $request->getParsedBodyParam('name', '');;


    $reviewer->name=$_name;
    $reviewer->created_at = date('Y-m-d H:i:s');

    $reviewer->save();

    if ($reviewer->id) {
        $payload = [
            'reviewerId' => $reviewer->id,
            'reviewer_uri' => '/reviewers/' . $reviewer->id
        ];
        return $response->withStatus(201)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->post('/movies/{id}/reviews', function (Request $request, Response $response, $args) {
    $id = intval($args['id']);
    $review = new Review();
    $_reviewer_id = $request->getParsedBodyParam('reviewerId', '');
    $_review = $request->getParsedBodyParam('review', '');
    $_movie_id = var_export($id, true);
    $_created_at = date('Y-m-d H:i:s');
    $_rating = $request->getParsedBodyParam('rating', '');
    $review->reviewerId = $_reviewer_id;
    $review->review = $_review;
    $review->movieId = $_movie_id;
    $review->created_at = $_created_at;
    $review->rating = $_rating;

//echo $_movie_id;
//die();
    $review->save();

    if ($review->id) {
        $payload = [
            'reviewId' => $review->id,
            'review_uri' => '/reviews/' . $review->id
        ];
        return $response->withStatus(201)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->post('/studios', function (Request $request, Response $response, $args) {
    $studio = new Studio();
   $_name = $request->getParsedBodyParam('name', '');
   $_description = $request->getParsedBodyParam('description', '');
   $_founding_date = $request->getParsedBodyParam('foundingDate', '');
    $studio->name = $_name;
    $studio->description = $_description;
    $studio->foundingDate = $_founding_date;

    $studio->save();

    if ($studio->id) {
        $payload = [
            'studioId' => $studio->id,
            'studio_uri' => '/studios/' . $studio->id
        ];
        return $response->withStatus(201)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
// END POST METHODS


//PATCH METHODS
$app->patch('/movies/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $movie = Movie::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $movie->$field = $value;
    }

    $movie->save();

    if ($movie->id) {
        $payload = [
            'movieId' => $movie->id,
            'movieName' => $movie->movieName,
            'releaseDate' => $movie->releaseDate,
            'studioId' => $movie->studioId,
            'directorId' => $movie->directorId,
            'movie_uri' => '/movies/' . $movie->id
        ];
        return $response->withStatus(200)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});

$app->patch('/genres/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $genre = Genre::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $genre->$field = $value;
    }

    $genre->save();

    if ($genre->id) {
        $payload = [
            'id' => $genre->id,
            'genreName' => $genre->genreName,
            'description' => $genre->description,
            'genre_uri' => '/genres/' . $genre->id
        ];
        return $response->withStatus(200)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->patch('/reviewers/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $reviewer = Reviewer::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $reviewer->$field = $value;
    }

    $reviewer->save();

    if ($reviewer->id) {
        $payload = [
            'id' => $reviewer->id,
            'name' => $reviewer->name,
            'createdAt' => $reviewer->created_at,
            'reviewers_uri' => '/reviewers/' . $reviewer->id
        ];
        return $response->withStatus(200)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->patch('/reviews/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $review = Review::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $review->$field = $value;
    }

    $review->save();

    if ($review->id) {
        $payload = [
            'id' => $review->id,
            'reviewerId' => $review->reviewerId,
            'review' => $review->review,
            'movieId' => $review->movieId,
            'createdAt' => $review->created_at,
            'rating' => $review->rating,
            'review_uri' => '/reviews/' . $review->id
        ];
        return $response->withStatus(200)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
$app->patch('/studios/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $studio = Studio::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $studio->$field = $value;
    }

    $studio->save();

    if ($studio->id) {
        $payload = [
            'id' => $studio->id,
            'name' => $studio->name,
            'description' => $studio->description,
            'foundingDate' => $studio->foundingDate,
            'studios_uri' => '/studios/' . $studio->id
        ];
        return $response->withStatus(200)->withJson($payload);
    } else {
        return $response->withStatus(500);
    }
});
//END OF PATCH METHODS


//DELETE METHODS
$app->delete('/movies/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $movie = Movie::find($id);
    $movie->delete();
    if ($movie->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Movie '/movies/$id' has been deleted.");
    }
});
$app->delete('/directors/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $director = Director::find($id);
    $director->delete();
    if ($director->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Director '/directors/$id' has been deleted.");
    }
});
$app->delete('/genres/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $genre = Genre::find($id);
    $genre->delete();
    if ($genre->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("genre '/genres/$id' has been deleted.");
    }
});
$app->delete('/reviewers/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $reviewer = Reviewer::find($id);
    $reviewer->delete();
    if ($reviewer->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Reviewer '/reviewers/$id' has been deleted.");
    }
});
$app->delete('/reviews/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $review = Review::find($id);
    $review->delete();
    if ($review->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Review '/reviews/$id' has been deleted.");
    }
});
$app->delete('/studios/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $studio = Studio::find($id);
    $studio->delete();
    if ($studio->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Studio '/studios/$id' has been deleted.");
    }
});


//END OF DELETE METHODS
$app->run();