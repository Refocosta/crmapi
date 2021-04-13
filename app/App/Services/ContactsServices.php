<?php namespace App\Services;
use App\Models\Contact;
use App\Controllers\TracingsController;
use App\Controllers\MailerController;
use Illuminate\Database\QueryException;
use Exceptions\ContactsException;
class ContactsServices
{
    private $tracingController;
    private $emailCotroller;

    public function __construct()
    {
        $this->tracingController = new TracingsController();
        $this->emailCotroller = new MailerController();
    }

    public function storeContactsWithChannels(array $channelId, int $contactId)
    {
        try {
            if (count($channelId) > 0) {
                $contact = Contact::find($contactId);
                for ($i = 0; $i < count($channelId); $i++) { 
                    $contact->channels()->attach($channelId[$i]);
                }
            }
        } catch (QueryException $e) {
            throw new ContactsException('CONTACTOS_SERVICE_ERR ATACH', 500);
        }
    }

    public function removeContactsWithChannels(array $channelIdDel, int $contactId)
    {
        try {
            if (count($channelIdDel) > 0) {
                $contact = Contact::find($contactId);
                for ($i = 0; $i < count($channelIdDel); $i++) {
                    $contact->channels()->detach($channelIdDel[$i]);
                }
            }
        } catch (QueryException $e) {
            throw new ContactsException('CONTACTOS_SERVICE_ERR DETACH', 500);
        }
        
    }

    public function storeContactsWithTypesChannels(array $typeChannelId, int $contactId)
    {
        try {
            if (count($typeChannelId) > 0) {
                $contact = Contact::find($contactId);
                for ($i = 0; $i < count($typeChannelId); $i++) {
                    $contact->typesChannels()->attach($typeChannelId[$i]);
                }
            }
        } catch (QueryException $e) {
            throw new ContactsException('CONTACTOS_SERVICE_ERR ATACH', 500);
        }
    }

    public function storeContactInTracing(array $array, int $type)
    {
        $this->tracingController->storeFromSystem($array, $type);
    }

    public function deleteTracingsWhenContactDeleted(Contact $contact)
    {
        try {
            for ($i = 0; $i < count($contact->tracings); $i++) {
            $contact->tracings[$i]->Status = 0;
            $contact->tracings[$i]->save();
        }
        } catch (\Illuminate\Database\QueryException $e) {
            throw new ContactsException($e->getMessage(), 500);
        }
    }

    public function sendEmailNotification(array $data)
    {
        $this->emailCotroller->mailFromSystem($data);
    }
}