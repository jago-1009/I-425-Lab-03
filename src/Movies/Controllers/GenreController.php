<?php

namespace Movies\Controllers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Genre;
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
}