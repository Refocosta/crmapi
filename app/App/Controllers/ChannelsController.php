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
        return $this->response($this->channel->all(), 200, $response);
    }

    public function store(Request $request,  Response $response, array $args): Response
    {
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'   => v::notEmpty()->stringType()->length(1, 45),
            'Status' => v::notEmpty()->intType()->length(1, 1)
        ])) {
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

        return $this->response([
            "id"     => $this->channel->Id,
            "name"   => $this->channel->Name,
            "status" => $this->channel->Status
        ], 201, $response);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        return $this->response($this->channel->find($id), 200, $response);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'   => v::optional(v::stringType()->length(1, 45)),
            'Status' => v::optional(v::notEmpty()->intType()->length(1, 1))
        ])) {
            throw new ChannelsException('Request enviado incorrecto', 400);
        }

        $record = $this->channel->find($id);
        $record->Name = (!empty($post['Name'])) ? $post['Name'] : $record->Name;
        $record->Status = (!empty($post['Status'])) ? $post['Status'] : $record->Status;
        $record->updated_at = Carbon::now('America/Bogota');
        $record->save();
        return $this->response([
            "id"    => $record->Id,
            "name"  => $record->Name,
            "Status"=> $record->Status
        ], 200, $response);

    }

    public function delete()
    {
        
    }

    public function destroy(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $record = $this->channel->find($id);
        $record->delete();
        return $this->response('OK ' . $id, 200, $response);
    }

    private function validate(array $post, array $rules): bool
    {
        self::validateRequest($post, $rules);
        if (self::failded()) {
            return false;
        }
        return true;
    }

    public function __destruct()
    {
        $this->channel = null;
    }
}