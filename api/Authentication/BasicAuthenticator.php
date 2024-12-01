<?php

namespace Movies\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Reviewer;


class BasicAuthenticator {
    public function __invoke(Request $request, Response $response, callable $next) {
        if (!$request->hasHeader('Authorization')) {
            $results = array(
                'status' => 'Authorization Header not Available'
            );
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
    }
    $auth = $request->getHeader('Authorization');
    $apikey = trim(substr($auth[0], 6));
        $decoded = base64_decode($apikey);

        if (!$decoded || strpos($decoded, ':') === false) {
            $results = ['status' => 'Invalid Authorization Token'];
            return $response->withJson($results, 400, JSON_PRETTY_PRINT);
        }
    list($username, $password) = explode(':', $decoded, 2);

    if (!Reviewer::AuthenticateReviewer($username, $password)) {
        $results = array("status" => "Authentication failed");
        return $response->withHeader('WWW-Authenticate', 'Basic realm="MovieAPI')->withJson($results, 401, JSON_PRETTY_PRINT);
    }

    $response = $next($request, $response);
    return $response;

}
}