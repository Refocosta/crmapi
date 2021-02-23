<?php namespace App\Controllers;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class ChannelsController
{
    public function index(Request $request,  Response $response, array $args): Response
    {
        $data = [
            "status" => 200,
            "error"  => false,
            "data"   => [
                [
                    "channel" => [
                        "socialMedia" => [
                            "Name" => "Facebook"
                        ]
                    ]
                ]
            ],
        ];
        return $response->withJson($data, 200);
    }
}