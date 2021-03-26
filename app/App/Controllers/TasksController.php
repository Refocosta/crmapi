<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\{Request, Response};
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\TasksException;
use App\Models\Task;
class TasksController extends BaseController
{
    private $task;

    public function __construct()
    {
        $this->task = new Task();
    }

    public function index(Request $request, Response $response, array $args) :Response
    {
        return $this->response($this->task->with('tracings')->where('Status', '<>', 0)->get(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Description' =>  v::notEmpty()->stringType()->length(1, 45),
            'Status'      =>  v::notEmpty()->intType()->length(1, 1),
            'TracingsId'  =>  v::notEmpty()->intType(),
            "TypesTasksId" => v::notEmpty()->intType()
        ])) {
            throw new TasksException('Request enviado incorrecto', 400);
        }

        $this->task->Description = $post['Description'];
        $this->task->Status = $post['Status'];
        $this->task->TracingsId = $post['TracingsId'];
        $this->task->TypesTasksId = $post['TypesTasksId'];
        $this->task->DeadLine = Carbon::parse($post['DeadLine']);
        $responseInsert = $this->task->save();

        if (!$responseInsert) {
            throw new TasksException('Ha ocurrido un error', 500);
        }

        return $this->response([
            "Id" => $this->task->Id,
            "Description" => $this->task->Description,
            "Status" => $this->task->Status,
        ], 201, $response);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        
        $record = $this->task->with('tracings')->with('typesTasks')->where('Status', '<>', 0)->find($id);

        if ($record === null) {
            throw new TasksException('El registro no existe', 404);
        }

        return $this->response($record, 200, $response);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Description'  => v::optional(v::notEmpty()->stringType()->length(1, 45)),
            'Status'       => v::optional(v::notEmpty()->intType()->length(1, 1)),
            'TracingsId'   => v::optional(v::notEmpty()->intType()),
            'TypesTasksId' => v::optional(v::notEmpty()->intType())
        ])) {
            throw new TasksException('Request enviado incorrecto', 400);
        }

        $record = $this->task->find($id);

        $record->Description = (!empty($post['Description'])) ? $post['Description'] : $record->Description;
        $record->Status = (!empty($post['Status'])) ? $post['Status'] : (int) $record->Status;
        $record->TracingsId = (!empty($post['TracingsId'])) ? $post['TracingsId'] : (int) $record->TracingsId;
        $record->TypesTasksId = (!empty($post['TypesTasksId'])) ? $post['TypesTasksId'] : (int) $record->TypesTasksId;
        $record->DeadLine = (!empty($post['DeadLine'])) ? $post['DeadLine'] : $record->DeadLine;
        $record->updated_at = Carbon::now('America/Bogota');
        $responseUpdate = $record->save();

        if (!$responseUpdate) {
            throw new TasksException('Ha ocurrido un error', 500);
        }

        return $this->response([
            "id"          => $record->Id,
            "Description" => $record->Description,
            "Status"      => $record->Status
        ], 200, $response);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $record = $this->task->find($id);

        if ($record === null) {
            throw new TasksException('El registro no existe', 404);
        }

        $record->Status = 0;
        $responseDelete = $record->save();

        if (!$responseDelete) {
            throw new TasksException('Ha ocurrido un error', 500);
        }

        return $this->response('OK ' . $id, 200, $response);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->task->find($id);

        if ($record === null) {
            throw new TasksException('El registro no existe', 404);
        }

        $responseDestroy = $record->delete();

        if (!$responseDestroy) {
            throw new TasksException('Ha ocurrido un error', 500);
        }

        return $this->response('OK ' . $id, 200, $response);
    }

    public function __destruct()
    {
        $this->task = null;
    }
}