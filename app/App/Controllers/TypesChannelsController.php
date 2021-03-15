<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\TypesChannelsException;
use App\Models\TypeChannel;
class TypesChannelsController extends BaseController
{

    private $typeChannel;

    public function __construct()
    {
        $this->typeChannel = new TypeChannel();
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        return $this->response($this->typeChannel->where('Status', 1)->get(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'       => v::notEmpty()->stringType()->length(1, 45),
            'ChannelsId' => v::notEmpty()->intType(),
            'Status'     => v::notEmpty()->intType()->length(1, 1)
        ])) {
            throw new TypesChannelsException('Request enviado incorrecto', 400);
        }
        
        $this->typeChannel->Name = $post["Name"];
        $this->typeChannel->ChannelsId = $post["ChannelsId"];
        $this->typeChannel->Status = $post['Status'];
        $responseInsert = $this->typeChannel->save();
        if (!$responseInsert) {
            throw new TypesChannelsException('Ha ocurrido un error', 500);
        }
        return $this->response([
            "Id"     => $this->typeChannel->Id,
            "Name"   => $this->typeChannel->Name,
            "Status" => $this->typeChannel->Status
        ], 201, $response);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args["id"];
        
        $record = $this->typeChannel->with(array('channels' => function($query) {
            $query->where('Status', 1);
        }))->where('Status', 1)->find($id);

        if ($record === null) {
            throw new TypesChannelsException('El registro no existe', 404);
        }

        return $this->response($record, 200, $response);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'       => v::optional(v::stringType()->length(1, 45)),
            'ChannelsId' => v::optional(v::intType()),
            'Status'     => v::optional(v::notEmpty()->intType()->length(1, 1))
        ])) {
            throw new TypesChannelsException('Request enviado incorrecto', 400);
        }

        $record = $this->typeChannel->find($id);

        if ($record === null) {
            throw new TypesChannelsException('El registro no existe', 404);
        }

        $record->Name = (!empty($post['Name'])) ? $post['Name'] : $record->Name;
        $record->ChannelsId = (!empty($post['ChannelsId'])) ? $post['ChannelsId'] : $record->ChannelsId;
        $record->Status = (!empty($post['Status'])) ? $post['Status'] : $record->Status;
        $record->updated_at = Carbon::now('America/Bogota');
        $record->save();
        return $this->response([
            "id"    => $record->Id,
            "name"  => $record->Name,
            "Status"=> $record->Status
        ], 200, $response);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->typeChannel->find($id);

        if ($record == null) {
            throw new TypesChannelsException("El registro no existe", 404);
        }

        $record->Status = 0;
        $record->save();
        return $this->response('OK ' . $id, 200, $response);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->typeChannel->find($id);

        if ($record === null) {
            throw new TypesChannelsException('El registro no existe', 404);
        }

        $record->delete();
        return $this->response('OK ' . $id, 200, $response);
    }

    //

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
        $this->typeChannel = null;
    }
}