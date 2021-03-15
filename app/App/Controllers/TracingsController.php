<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\TracingException;
use App\Models\Tracing;
class TracingsController extends BaseController
{

    private $tracing;

    public function __construct()
    {
        $this->tracing = new Tracing();
    }

    public function store(?Request $request, ?Response $response, ?array $array): Response
    {

    }

    public function storeFromSystem(array $array)
    {
        $this->tracing->Observation = "Se ha registrado automaticamente desde la plataforma CRM";
        $this->tracing->TypesObservationsId = $array["TypesObservationsId"];
        $this->tracing->ContactsId = $array["ContactsId"];
        $this->tracing->ChannelsId = $array["ChannelsId"];
        $this->tracing->UsersId = $array["UsersId"];
        $this->tracing->save();
    }
}