<?php
namespace Movies\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Director;
class DirectorController
{
    // Get all directors (Added search functionality
    public function index(Request $request, Response $response, $args)
    {
        $params = $request->getQueryParams();
        $results = Director::getAllDirectors($params);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    public function view(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $results = Director::getDirector($id);
        $code = array_key_exists('status', $results) ? 500 : 200;

        return $response->withStatus(200)->withJson($results);
    }
    public function viewMovie(Request $request, Response $response, $args) {
        $id = $args['id'];
        $results = Director::getMoviesbyDirector($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withStatus(200)->withJson($results);
    }
    public function create(Request $request, Response $response, $args) {
        $director = Director::createDirector($request);
        if ($director->id) {
            $payload = [
                'directorId' => $director->id,
                'director_uri' => '/directors/' . $director->id
            ];
            return $response->withStatus(201)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }
    public function update(Request $request, Response $response, $args) {
        $id = $args['id'];
        $entry = Director::getDirector($id);
        $params = $request->getParsedBody();
        $director = Director::updateDirector($entry, $params);
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
    }
    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];
        $entry = Director::getDirector($id);
        if ($entry) {
            $results = Director::deleteDirector($id);
            return $response->withStatus(200)->withJson($results);
        }
        else {
            return $response->withStatus(500);
        }
    }
}
