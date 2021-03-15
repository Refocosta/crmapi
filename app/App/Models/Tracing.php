<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Core\Base;
class Tracing extends Model
{
    public function __construct()
    {
        (new Base());
    }

    protected $primaryKey = 'Id';

    protected $connection = "crm";

    protected $table = "Tracings";

    public function contacts()
    {
        return $this->belongsTo(Contact::class, 'ContactsId', 'Id');
    }

    public function channels()
    {
        return $this->belongsTo(Channel::class, 'ChannelsId', 'Id');
    }
}