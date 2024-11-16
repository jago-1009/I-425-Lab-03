<?php
/**
 * Author:Ran Chang
 * Date: 8/8/2019
 * File: MyAuthenticator.php
 * Description:
 */
namespace Movies\Authentication;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Movies\Models\Reviewer;
class Authenticator
{

    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$request->hasHeader('MovieAPI-Authorization')) {
            $results = array(
                'status' => 'Authorization Header not Available'
            );
            return $response->withJson($results, 404, JSON_PRETTY_PRINT);
        }

        $auth = $request->getHeader('MovieAPI-Authorization');
        list($username, $password) = explode(':', $auth[0]);

        if (!User::authenticateUser($username, $password)) {
            $results = array("status" => "Authentication failed");
            return $response->withJson($results, 401, JSON_PRETTY_PRINT);
        }

        $response = $next($request, $response);
        return $response;
    }
}

