<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Core\Base;
class Channel extends Model
{
    public function __construct()
    {
        (new Base());
    }

    protected $primaryKey = 'Id';

    protected $connection = "crm";

    protected $table = "Channels";

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'CotactsChannels', 'ChannelsId', 'ContactsId');
    }
}