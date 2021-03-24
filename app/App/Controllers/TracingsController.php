<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\TracingsException;
use App\Models\Tracing;
class TracingsController extends BaseController
{

    private $tracing;

    public function __construct()
    {
        $this->tracing = new Tracing();
    }

    public function index(Request $request, Response $response, array $args) :Response
    {
        return $this->response($this->tracing->with('contacts')->with('typesChannels')->with('typesObservations')->get(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'TypesObservationsId' => v::notEmpty()->intType(),
            'ContactsId'          => v::notEmpty()->intType(),
            'TypesChannelsId'     => v::notEmpty()->intType(),
            'UsersId'             => v::notEmpty()->intType(),
            'Observation'         => v::notEmpty()
        ])) {
            throw new TracingsException('Request enviado incorrecto', 400);
        }

        $this->tracing->Observation = $post["Observation"];
        $this->tracing->TypesObservationsId = $post["TypesObservationsId"];
        $this->tracing->ContactsId = $post["ContactsId"];
        $this->tracing->TypesChannelsId = $post["TypesChannelsId"];
        $this->tracing->UsersId = $post["UsersId"];
        $responseStore = $this->tracing->save();
        
        if (!$responseStore) {
            throw new TracingsException('Ha ocurrido un error', 500);
        }

        return $this->response('Registrado correctamente', 201, $response);
    }

    public function storeFromSystem(array $array)
    {
        $this->tracing->Observation = "Se ha registrado automaticamente desde la plataforma CRM";
        $this->tracing->TypesObservationsId = $array["TypesObservationsId"];
        $this->tracing->ContactsId = $array["ContactsId"];
        $this->tracing->TypesChannelsId = $array["TypesChannelsId"];
        $this->tracing->UsersId = $array["UsersId"];
        $responseStoreSystem = $this->tracing->save();
        if (!$responseStoreSystem) {
            throw new TracingsException('Ha ocurrido un error', 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $record = $this->tracing->with('contacts')->with('typesChannels')->with('typesObservations')->get()->find($id);

        if ($record === null) {
            throw new TracingsException('El registro no existe', 404);
        }

        return $this->response($record, 200, $response);
    }

    public function __destruct()
    {
        $this->tracing = null;
    }
}