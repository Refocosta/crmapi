<?php namespace App\Services;
use App\Models\Channel;
use App\Models\Contact;
use App\Controllers\TracingsController;
class ContactsServices
{
    private $tracingController;

    public function __construct()
    {
        $this->tracingController = new TracingsController();
    }

    public function storeContactsWithChannels(array $channelId, int $contactId): ?bool
    {
        if (count($channelId) > 0) {
            $contact = Contact::find($contactId);
            for ($i = 0; $i < count($channelId); $i++) { 
                $responseAttach = $contact->channels()->attach($channelId[$i]);
            }
            return $responseAttach;
        }
        return true;
    }

    public function removeContactsWithChannels(array $channelIdDel, int $contactId): ?bool
    {
        if (count($channelIdDel) > 0) {
            $contact = Contact::find($contactId);
            for ($i = 0; $i < count($channelIdDel); $i++) {
                $responseDetach = $contact->channels()->detach($channelIdDel[$i]);
            }
        }
        return true;
    }

    public function storeContactsWithTypesChannels(array $typeChannelId, int $contactId): ?bool
    {
        if (count($typeChannelId) > 0) {
            $contact = Contact::find($contactId);
            for ($i = 0; $i < count($typeChannelId); $i++) {
                $responseAttach = $contact->typesChannels()->attach($typeChannelId[$i]);
            }
        }
        return true;
    }

    public function storeContactInTracing(array $array)
    {
        $this->tracingController->storeFromSystem($array);

    }
}