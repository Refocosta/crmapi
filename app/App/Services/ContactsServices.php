<?php namespace App\Services;
use App\Models\Channel;
use App\Models\Contact;
class ContactsServices
{
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
}