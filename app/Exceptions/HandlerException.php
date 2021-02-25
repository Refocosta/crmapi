<?php namespace Exceptions;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HandlerException extends \Slim\Handlers\Error
{
    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        $status = $exception->getCode();
        $class = new \ReflectionClass(get_class($exception));
        $data = [
            "status" => $status,
            "error"  => true,
            "class"  => $class->getName(),
            "message"=> $exception->getMessage()
        ];
        $body = json_encode($data);
        $response->getBody()->write((string) $body);
        return $response
            ->withStatus($status)
            ->withHeader('Content-type', 'application/problem+json');
    }
}