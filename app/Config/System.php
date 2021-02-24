<?php namespace Config;
use Config\Env;
class System extends Env 
{
    public static function system(): bool
    {
        return (self::env()['SIS_STATUS'] === 'true') ? true : false;
    }
}
