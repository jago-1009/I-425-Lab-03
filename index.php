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
            "movies" => [
                "Get all movies" => "/movies",
                "Get a single movie" => "/movies/{id}",
                "Get reviews for a specific movie by movie" => "/movies/{id}/reviews",
            ],
            "reviews" => [
                "Get all reviews" => "/reviews",
                "Get a single review" => "/reviews/{id}",
            ],
            "reviewers" => [
                "Get all reviewers" => "/reviewers",
                "Get a single reviewer" => "/reviewers/{id}",
                "Get all reviews by specific reviewer" => "/reviewers/{id}/reviews",
            ],
            "directors" => [
                "Get all directors" => "/directors",
                "Get a single director" => "/directors/{id}",
                "Get all movies by a specific director" => "/directors/{id}/movies"
            ],
            "genres" => [
                "Get all genres" => "/genres",
                "Get a single genre" => "/genres/{id}",
                "Get all movies in a specific genre by genre" => "/genre/{id}/movies"
            ],
            "studios" => [
                "Get all studios" => "/studios",
                "Get a single studio" => "/studios/{id}",
                "Get all movies by a specific studio" => "/studios/{id}/movies"
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

// GET DIRECTORS FUNCTIONS

// Get all directors
$app->get('/directors', function ($request, $response, $args) {
    $directors = Director::all();
    $page = $request->getQueryParams()['page'] ?? 1;
    $perPage = $request->getQueryParams()['perPage'] ?? 10;

    //Sort
    $sortColumn = $request->getQueryParams()['sort'] ?? 'id';
    $sortOrder = $request->getQueryParams()['order'] ?? 'ASC';
    $validSortColumns = ['id', 'name', 'birthdate'];
    if(!in_array($sortColumn, $validSortColumns)) {
        $sortColumn = 'id';
    }

    $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';

    //Offset
    $offset = ($page - 1) * $perPage;

    //Fetch w/ pagination & sort
    $directors = Director::orderBy($sortColumn, $sortOrder)
        ->limit($perPage)
        ->offset($offset)
        ->get();

    //count
    $totalDirectors = Director::count();

    $payload = [];
    foreach ($directors as $director) {
        $payload[$director->id] = [
            'name' => $director->name,
            'bio' => $director->bio,
            'birthDate' => $director->birthDate,
            'deathDate' => $director->deathDate,
        ];
    }

    //pagination data
    $responsePayload = [
        'data' => $payload,
        'pagination' => [
            'total' => $totalDirectors,
            'page' => (int)$page,
            'perPage' => (int)$perPage,
            'lastPage' => ceil($totalDirectors / $perPage),
        ]
    ];

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
    return $response->withStatus(200)->withJson($payload);
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

$app->post('/directors', function (Request $request, Response $response, $args) {
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

    if ($director->id) {
        $payload = [
            'directorId' => $director->id,
            'director_uri' => '/directors/' . $director->id
        ];
        return $response->withStatus(201)->withJson($payload);
    } else {
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
$app->patch('/directors/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $director = Director::findOrFail($id);
    $params = $request->getParsedBody();

    foreach ($params as $field => $value) {
        $director->$field = $value;
    }

    $director->save();

    if ($director->id) {
        $payload = [
            'id' => $director->id,
            'name' => $director->name,
            'bio' => $director->bio,
            'birthDate' => $director->birthDate,
            'deathDate' => $director->deathDate,
            'director_uri' => '/directors/' . $director->id
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
    if ($movie->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Movie '/movies/$id' has been deleted.");
    }
});
$app->delete('/directors/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $director = Director::find($id);
    if ($director->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Director '/directors/$id' has been deleted.");
    }
});
$app->delete('/genres/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $genre = Genre::find($id);
    if ($genre->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("genre '/genres/$id' has been deleted.");
    }
});
$app->delete('/reviewers/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $reviewer = Reviewer::find($id);
    if ($reviewer->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Reviewer '/reviewers/$id' has been deleted.");
    }
});
$app->delete('/reviews/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $review = Review::find($id);
    if ($review->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Review '/reviews/$id' has been deleted.");
    }
});
$app->delete('/studios/{id}', function ($request, $response, $args) {
    $id = $args['id'];
    $studio = Studio::find($id);
    if ($studio->exists) {
        return $response->withStatus(500);
    } else {
        return $response->withStatus(204)->getBody()->write("Studio '/studios/$id' has been deleted.");
    }
});


//END OF DELETE METHODS
$app->run();