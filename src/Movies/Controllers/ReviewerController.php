<?php

namespace Movies\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Reviewer;
use Movies\Validations\Validator;

class ReviewerController
{
    public function index(Request $request, Response $response, $args)
    {
        $results = Reviewer::getAllReviewers();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    public function view(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $results = Reviewer::getReviewer($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
    public function getReviews(Request $request, Response $response, $args) {
        $id = $args['id'];
        $results = Reviewer::getReviews($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    public function create(Request $request, Response $response, $args)
    {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $reviewer = Reviewer::createReviewer($request);
        if ($reviewer->id) {
            $payload = [
                'reviewerId' => $reviewer->id,
                'reviewer_uri' => '/reviewers/' . $reviewer->id
            ];
            return $response->withStatus(201)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $id = $args['id'];
        $entry = Reviewer::find($id);
        $params = $request->getParsedBody();
        $reviewer = Reviewer::updateReviewer($entry, $params);
        if ($reviewer->id) {
            $payload = [
                'id' => $reviewer->id,
                'name' => $reviewer->name,
                'created_at' => $reviewer->created_at,
            ];
            return $response->withStatus(200)->withJson($payload);
        } else {
            return $response->withStatus(500);
        }
    }

    public function delete(Request $request, Response $response, $args)
    {
        $validation = Validator::validateUser($request);
        if (!$validation) {
            $results = [
                'status' => 'failed',
                'errors' => Validator::getErrors()
            ];
            return $response->withStatus(500)->withJson($results);
        }
        $id = $args['id'];
        $reviewer = Reviewer::find($id);
        if ($reviewer) {
            $results = Reviewer::deleteReviewer($id);
            return $response->withStatus(200)->withJson($results);
        } else {
            return $response->withStatus(404);
        }
    }
   
}
