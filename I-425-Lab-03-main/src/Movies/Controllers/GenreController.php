<?php

namespace Movies\Controllers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Genre;
use Movies\Validations\Validator;
class GenreController
{
    public function index(Request $request, Response $response, $args) {
        $results = Genre::getAllGenres();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
}

    public function view(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $results = Genre::getGenre($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function viewMovie(Request $request, Response $response, $args) {
        $id = $args['id'];
        $results = Genre::getMoviesbyGenre($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function create(Request $request, Response $response, $args) {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $genre = Genre::createGenre($request);    
        if ($genre->id) {
            $payload = [
                'genreId' => $genre->id,
                'genre_uri' => '/genres/' . $genre->id
            ];
            return $response->withStatus(201)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }
    public function update(Request $request, Response $response, $args) {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $id = $args['id'];
        $entry = Genre::getGenre($id);
        $params = $request->getParsedBody();
        $genre = Genre::updateGenre($entry, $params);
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
    }
    public function delete(Request $request, Response $response, $args) {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $id = $args['id'];
        $entry = Genre::getGenre($id);
        if ($entry) {
            $results = Genre::deleteGenre($id);
            return $response->withStatus(200)->withJson($results);
        }
        else {
            return $response->withStatus(500);
        }
    }
}