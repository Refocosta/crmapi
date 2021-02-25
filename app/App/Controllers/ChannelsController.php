<?php namespace App\Controllers;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use Respect\Validation\Validator as v;
use Exceptions\ChannelsException;
use App\Models\Channel;

class ChannelsController
{

    use \Core\Validator;

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
        $post = $request->getParsedBody();

        self::validateRequest($post, [
            'Name'   => v::notEmpty()->stringType()->length(1, 45),
            'Status' => v::notEmpty()->intType()->length(1, 1)
        ]);

        if (self::failded()) {
            throw new ChannelsException('Request enviado incorrecto', 400);
        }

        Channel::insert([
            'Name' => $post['Name'],
            'Status' => $post['Status'],
            "created_at" => Carbon::now('America/Bogota'),
            "updated_at" => Carbon::now('America/Bogota')
        ]);
    }
}