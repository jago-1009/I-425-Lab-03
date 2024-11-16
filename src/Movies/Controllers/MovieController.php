<?php 
namespace Movies\Controllers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Movie;


class MovieController {
    public function index(Request $request, Response $response) {
        $results = Movie::getAllMovies();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function view(Request $request, Response $response, $args) {
        $id = $args['id'];
        $results = Movie::getMovie($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function viewGenre(Request $request, Response $response, $args) {
        $id = $args['id'];
        $results = Movie::getMoviesbyGenre($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function create(Request $request, Response $response, $args) {
        $movie = Movie::createMovie($request);
        if ($movie->id) {
            $payload = [
                'movieId' => $movie->id,
                'movie_uri' => '/movies/' . $movie->id
            ];
            return $response->withStatus(201)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }
    public function update(Request $request, Response $response, $args) {
        $id = $args['id'];
        $entry = Movie::getMovie($id);
        $params = $request->getParsedBody();
        $movie = Movie::updateMovie($entry, $params);
        if ($movie->id) {
            $payload = [
                'id' => $movie->id,
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
    }
    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];
        $entry = Movie::getMovie($id);
        if ($entry) {
            $results = Movie::deleteMovie($id);
            return $response->withStatus(200)->withJson($results);
        }
        else {
            return $response->withStatus(500);
        }
    }
    

}