<?php


namespace Movies\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Reviewer;

class JWTAuthenticator
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$request->hasHeader('Authorization')) {
            $results = array('status' => 'Authorization header not available');
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        $auth = $request->getHeader('Authorization');

        $token = substr($auth[0], strpos($auth[0], ' ') + 1);

        if (!Reviewer::validateJWT($token)) {
            $results = array("status" => "Authentication failed");
            return $response->withJson($results, 401, JSON_PRETTY_PRINT);

        }

        return $next($request, $response);
    }
}