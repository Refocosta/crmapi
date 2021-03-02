<?php namespace App\Controllers;
use App\Controllers\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;
use Exceptions\ContactsException;
use App\Models\Contact;
use App\Models\Channel;

class ContactsController extends BaseController
{

    private $contact;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->channel = new Channel();
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        return $this->response($this->contact::all(), 200, $response);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $post = $request->getParsedBody();
        $channelId = $post['ChannelId'];
        $this->contact->Name = $post['Name'];
        $this->contact->Cellphone = $post['Cellphone'];
        $this->contact->Email = $post['Email'];
        $this->contact->Petition = $post['Petition'];
        $this->contact->Status = $post['Status'];
        $this->contact->save();
        $channel = $this->channel::find($channelId);
        $channel->contacts()->attach($this->contact->Id);
        return $response;
    }

    public function show()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function destroy()
    {

    }

}