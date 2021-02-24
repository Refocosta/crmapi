<?php namespace Config;
final class Key
{
    public static function key(): string
    {
        return $_ENV['SECRET'];
    }
}