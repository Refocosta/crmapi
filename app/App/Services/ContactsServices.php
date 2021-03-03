<?php namespace App\Services;
use App\Models\Channel;
class ContactsServices
{
    public function contactsWithChannels(array $channelId, int $contactId): ?bool
    {
        for ($i=0; $i < count($channelId); $i++) { 
            $channel = Channel::find($channelId[$i]['Id']);
            $responseAttach = $channel->contacts()->attach($contactId);
        }
        return $responseAttach;
    }
}