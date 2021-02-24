<?php namespace Config;
use Illuminate\Database\Capsule\Manager as Capsule;
abstract class Connections
{
    protected $connection;

    public function __construct()
    {
        $this->connection = new Capsule();
        $this->connection();
        $this->start();
    }

    private function connection()
    {
        $this->connection->addConnection([
            'driver'    => 'sqlsrv',
            'host'      => 'IT-PC',
            'database'  => 'crm',
            'username'  => 'sa',
            'password'  => 'local1230',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ], 'crm');
    }

    private function start()
    {
        $this->connection->setAsGlobal();
        $this->connection->bootEloquent();
    }

    abstract public function DB();

    public function __destruct()
    {
        $this->connection = null;
    }
}