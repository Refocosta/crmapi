<?php namespace App\Controllers;
use Slim\Http\Response;
abstract class BaseController
{
    use \Core\Validator;

    protected function response($data, int $status, Response $response): Response
    {
        $result = [
            "status" => $status,
            "error"  => false,
            "message"=> $data
        ];
        return $response->withJson($result, $status);
    }

    protected function validate(array $post, array $rules): bool
    {
        self::validateRequest($post, $rules);
        if (self::failded()) {
            return false;
        }
        return true;
    }

}