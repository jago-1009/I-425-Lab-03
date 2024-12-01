<?php

namespace Movies\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
class Logging
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
     error_log($request->getMethod() . ' -- ' . $request->getUri());
     $response = $next($request, $response);
     return $response;
    }
}