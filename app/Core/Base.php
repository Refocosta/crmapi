<?php namespace Core;
use Config\Connections;
class Base extends Connections
{
    protected function DB(): \Illuminate\Database\Capsule\Manager
    {
        return $this->connection;
    }
}