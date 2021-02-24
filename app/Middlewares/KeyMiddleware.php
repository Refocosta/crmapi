<?php namespace Middlewares;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Config\Key;
final class KeyMiddleware
{
    public function __invoke(Request $request, Response $response, $next): Response
    {
        if ($request->getHeaderLine('Authorization') == null) {
            return $response->withJson([
                "status" => 400,
                "error" => true,
                "Message" => "No esta autorizado"
            ], 400);
        }

        if ($request->getHeaderLine('Authorization') != Key::key()) {
            return $response->withJson([
                "status" => 401,
                "error" => true,
                "Message" => "No esta autorizado"
            ], 401);
        }
        $response = $next($request, $response);
        return $response;
    }
}