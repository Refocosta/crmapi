<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Core\Base;
class Contact extends Model
{
    public function __construct()
    {
        (new Base());
    }

    protected $primaryKey = 'Id';

    protected $connection = "crm";

    protected $table = "Contacts";

    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'CotactsChannels', 'ChannelsId', 'ContactsId');
    }

}