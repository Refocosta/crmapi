<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
use Exceptions\ContactsException;
use App\Models\Contact;
use App\Services\ContactsServices;

class ContactsController extends BaseController
{

    private $contact;
    private $contactService;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->contactService = new ContactsServices();
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        return $this->response($this->contact->with(array('channels' => function ($query){
            $query->where('Status', 1);
        }))->where('Status', 1)->get(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();
        if (!$this->validate($post, [
            'Name'      => v::notEmpty()->stringType()->length(1, 55),
            'Cellphone' => v::notEmpty()->stringType()->length(1, 15),
            'Email'     => v::notEmpty()->email()->length(1, 125),
            'Petition'  => v::notEmpty()->stringType(),
            'Status'    => v::notEmpty()->intType()->length(1, 1)
        ])) {
            throw new ContactsException('Request enviado incorrecto', 400);
        }

        $this->contact->Name = $post['Name'];
        $this->contact->Cellphone = $post['Cellphone'];
        $this->contact->Email = $post['Email'];
        $this->contact->Petition = $post['Petition'];
        $this->contact->Status = $post['Status'];
        $responseInsert  = $this->contact->save();
        $this->contactService->contactsWithChannels($post['ChannelId'], $this->contact->Id);

        if (!$responseInsert) {
            throw new ContactsException('Ha ocurrido un error', 500);
        }

        return $this->response([
            "Id"   => $this->contact->Id,
            "Name" => $this->contact->Name,
            "Cellphone" => $this->contact->Cellphone,
            "Email" => $this->contact->Email,
            "Petition" => $this->contact->Petition,
            "Status" => $this->contact->Status
        ], 201, $response);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->contact->with(array('channels' => function ($query){
            $query->where('Status', 1);
        }))->where('Status', 1)->get()->find($id);

        if ($record === null) {
            throw new ContactsException('El registro no existe', 404);
        }

        return $this->response($record, 200, $response);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $post = $request->getParsedBody();

        if (!$this->validate($post, [
            'Name'      => v::optional(v::notEmpty()->stringType()->length(1, 55)),
            'Cellphone' => v::optional(v::notEmpty()->stringType()->length(1, 15)),
            'Email'     => v::optional(v::notEmpty()->email()->length(1, 125)),
            'Petition'  => v::optional(v::notEmpty()->stringType()),
            'Status'    => v::optional(v::notEmpty()->intType()->length(1, 1))
        ])) {
            throw new ContactsException('Request enviado incorrecto', 400);
        }

        $record = $this->contact->find($id);

        if ($record === null) {
            throw new ContactsException('El registro no existe', 404);
        }

        $record->Name = $post['Name'];
        $record->Cellphone = $post['Cellphone'];
        $record->Email = $post['Email'];
        $record->Petition = $post['Petition'];
        $record->Status = $post['Status'];
        $record->updated_at = Carbon::now('America/Bogota');
        $record->save();
        return $response;


    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->contact->find($id);
        $record->Status = 0;
        $record->save();
        return $this->response('OK ' . $id, 200, $response);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $record = $this->contact->find($id);

        if ($record === null) {
            throw new ContactsException('El registro no existe', 404);
        }

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

}