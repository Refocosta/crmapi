<?php namespace App\Controllers;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Channel;
use Carbon\Carbon;

class ChannelsController
{
    public function index(Request $request,  Response $response, array $args): Response
    {
        $data = [
            "status" => 200,
            "error"  => false,
            "data"   => Channel::all()
        ];
        return $response->withJson($data, 200);
    }

    public function store(Request $request,  Response $response, array $args)
    {
        $name = "MAIL";
        $status = 1;
        Channel::insert([
            'Name' => $name,
            'Status' => $status,
            "created_at" => Carbon::now('America/Bogota'),
            "updated_at" => Carbon::now('America/Bogota')
        ]);
    }
}