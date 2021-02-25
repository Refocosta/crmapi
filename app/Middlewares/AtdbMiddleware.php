<?php namespace Middlewares;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Config\Atdb;
class AtdbMiddleware extends Atdb
{
    public function __invoke(Request $request, Response $response, $next): Response
    {
        if ($request->getHeaderLine('atdb') == null) {
            $response->getBody()->write('null');
            return $response;
        }
        
        if ($request->getHeaderLine('atdb') != self::Atdb()) {
            $response->getBody()->write('null');
            return $response;
        }
        
        $response = $next($request, $response);
        return $response;
    }
}