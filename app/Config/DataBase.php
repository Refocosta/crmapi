<?php namespace Config;
abstract class DataBase
{
    public static function dataBase(): array
    {
        return [
            "DATABASE_SERVER" => $_ENV["DATABASE_SERVER"],
            "DATABASE_DB"     => $_ENV["DATABASE_DB"],
            "DATABASE_USER"   => $_ENV["DATABASE_USER"],
            "DATABASE_PASS"   => $_ENV["DATABASE_PASS"]
        ];
    }
}