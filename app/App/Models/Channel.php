<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Core\Base;
class Channel extends Model
{
    public function __construct()
    {
        (new Base());
    }

    protected $connection = "crm";

    protected $table = "Channels";
}