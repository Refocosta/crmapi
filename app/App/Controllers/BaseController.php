<?php namespace App\Controllers;
use Slim\Http\Response;
abstract class BaseController
{
    use \Core\Validator;

    protected function response(array|string|int $data, int $status, Response $response): Response
    {
        $result = [
            "status" => $status,
            "error"  => false,
            "message"=> $data
        ];
        return $response->withJson($result, $status);
    }

}