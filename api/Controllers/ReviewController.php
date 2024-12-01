<?php

namespace Movies\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Review;
use Movies\Validations\Validator;

class ReviewController
{
    public function index(Request $request, Response $response, $args)
    {
        $results = Review::getAllReviews();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    public function view(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $results = Review::getReview($id);
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
        $review = Review::createReview($request);
        if ($review->id) {
            $payload = [
                'reviewId' => $review->id,
                'review_uri' => '/reviews/' . $review->id
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
        $review = Review::updateReview($id, $request);
        if ($review->id) {
            $payload = [
                'reviewId' => $review->id,
                'review_uri' => '/reviews/' . $review->id
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
        $entry = Review::getReview($id);
        if ($entry) {
            $results = Review::deleteReview($id);
            return $response->withStatus(200)->withJson($results);
        }
        else {
            return $response->withStatus(500);
        }
    }
}
