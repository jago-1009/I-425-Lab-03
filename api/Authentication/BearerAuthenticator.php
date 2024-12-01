<?php

namespace Movies\Authentication;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Token;



class BearerAuthenticator
{
    public function __invoke(Request $request, Response $response, $next)
    {
        // If the header named "Authorization" does not exist, display an error
        if (!$request->hasHeader('Authorization')) {
            $results = [
                'status' => 'Authorization header not available'
            ];
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        // Authorization header exists, retrieve the header and the header value
        $auth = $request->getHeader('Authorization');

        $token = substr($auth[0], strpos($auth[0], ' ') + 1);

        if (!Token::validateBearer($token)) {
            $results = [
                "status" => "Authentication failed"
            ];
            return $response->withJson($results, 401, JSON_PRETTY_PRINT);
        }

        // A user has been authenticated
        $response = $next($request, $response);
        return $response;
    }

}
