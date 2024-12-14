<?php 
namespace Movies\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Studio;
use Movies\Validations\Validator;

class StudioController
{
    public function index(Request $request, Response $response)
    {
        $studios = Studio::all();
        
        
        return $response->withJson($studios);
    }

    public function view(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $studio = Studio::getStudio($id);
        if (!$studio) {
            return $response->withStatus(404);
        }
       
        return $response->withJson($studio);
    }

    public function viewMovie(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $studio = Studio::getStudio($id);
        if (!$studio) {
            return $response->withStatus(404);
        }
        $movies = $studio->movies;
        $result = [];
        foreach ($movies as $movie) {
            $result[] = [
                'id' => $movie->id,
                'movieName' => $movie->movieName,
                'movie_uri' => '/movies/' . $movie->id
            ];
        }
        return $response->withJson($result);
    }

    public function create(Request $request, Response $response)
    {
        $params = $request->getParsedBody();
        $studio = Studio::createStudio($params);
        if ($studio->id) {
            $payload = [
                'id' => $studio->id,
                'studioName' => $studio->studioName,
                'studio_uri' => '/studios/' . $studio->id
            ];
            return $response->withStatus(201)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $entry = Studio::getStudio($id);
        $params = $request->getParsedBody();
        $studio = Studio::updateStudio($entry, $params);
        if ($studio->id) {
            $payload = [
                'id' => $studio->id,
                'studioName' => $studio->studioName,
                'studio_uri' => '/studios/' . $studio->id
            ];
            return $response->withStatus(200)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }

    public function delete($request, $response, $args)
    {
        $studioId = $args['id'];

        $studio = Studio::find($studioId);

        if (!$studio) {
            error_log('Studio not found with ID: ' . $studioId);
            return $response->withStatus(404)->write('Studio not found.');
        }


        try {
            Studio::deleteStudio($studio);
            error_log('Studio deleted successfully: ' . $studioId);
            return $response->withStatus(204);
        } catch (Exception $e) {
            error_log('Error deleting studio: ' . $e->getMessage());
            return $response->withStatus(500)->write('Failed to delete studio.');
        }
    }

}