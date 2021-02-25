<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\ChannelsException;
use App\Models\Channel;
class ChannelsController extends BaseController
{

    private $channel;

    public function __construct()
    {
        $this->channel = new Channel();
    }

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

        $this->channel->Name = $post['Name'];
        $this->channel->Status = $post['Status'];
        $this->channel->created_at = Carbon::now('America/Bogota');
        $this->channel->updated_at = Carbon::now('America/Bogota');
        $responseInsert = $this->channel->save();
        if (!$responseInsert) {
            throw new ChannelsException('Ha ocurrido un error', 500);
        }

        return $this->response($this->channel->id, 200, $response);
    }

    public function __destruct()
    {
        $this->channel = null;
    }
}