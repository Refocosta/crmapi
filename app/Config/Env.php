<?php namespace Config;
abstract class Env
{
    private static $env;
    
    public static function env(): array
    {
        self::$env = \Dotenv\Dotenv::createImmutable("../");
        return self::$env->load();
    }
}