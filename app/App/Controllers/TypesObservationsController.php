<?php namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\TypeObservation;
use Slim\Http\{Request, Response};
use Respect\Validation\Validator as v;
use Exceptions\TypesObservationsException;
class TypesObservationsController extends BaseController
{
    private $typeObservation;

    public function __construct()
    {
        $this->typeObservation = new TypeObservation();
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        return $this->response($this->typeObservation->where('Status', 1)->get(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();
        
        if (!$this->validate($post, [
            'Name'   => v::notEmpty()->stringType()->length(1, 55),
            'Status' => v::notEmpty()->intType()->length(1, 1)
        ])) {
            throw new TypesObservationsException('Request enviado incorrecto', 400);
        }

        $this->typeObservation->Name = $post['Name'];
        $this->typeObservation->Status = $post['Status'];
        $responseInsert = $this->typeObservation->save();

        if ($responseInsert === null) {
            throw new TypesObservationsException('Ha ocurrido un error', 500);
        }

        return $this->response([
            "Id"     => $this->typeObservation->Id,
            "Name"   => $this->typeObservation->Name,
            "Status" => $this->typeObservation->Status
        ], 201, $response);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->typeObservation->where('Status', 1)->get()->find($id);

        if ($record === null) {
            throw new TypesObservationsException('El registro no existe', 404);
        }

        return $this->response($record, 200, $response);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'   => v::optional(v::notEmpty()->stringType()->length(1, 55)),
            'Status' => v::optional(v::notEmpty()->intType()->length(1, 1))
        ])) {
            throw new TypesObservationsException('Request enviado incorrecto', 400);
        }

        $record = $this->typeObservation->where('Status', 1)->get()->find($id);

        if ($record === null) {
            throw new TypesObservationsException('El registro no existe', 404);
        }

        $record->Name   = (!empty($post['Name'])) ? $post['Name'] : $record->Name;
        $record->Status = (!empty($post['Status'])) ? $post['Status'] : (int) $record->Status;
        $responseUpdate = $record->save();

        if (!$responseUpdate) {
            throw new TypesObservationsException('Ha ocurrido un error', 500);
        }

        return $this->response([
            "id"    => $record->Id,
            "name"  => $record->Name,
            "Status"=> $record->Status
        ], 200, $response);

    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->typeObservation->where('Status', 1)->get()->find($id);

        if ($record === null) {
            throw new TypesObservationsException('El registro no existe', 404);
        }

        $record->Status = 0;
        $responseDelete = $record->save();

        if (!$responseDelete) {
            throw new TypesObservationsException('Ha ocurrido un error', 500);
        }
        
        return $this->response('OK '. $id, 200, $response);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->typeObservation->where('Status', 1)->get()->find($id);

        if ($record === null) {
            throw new TypesObservationsException('El registro no existe', 404);
        }

        $responseDestroy = $record->delete();
        if (!$responseDestroy) {
            throw new TypesObservationsException('Ha ocurrido un error', 500);
        }

        return $this->response('OK '. $id, 200, $response);
    }
}