<?php namespace Config;
use Illuminate\Database\Capsule\Manager as Capsule;
use Config\DataBase;
abstract class Connections extends DataBase
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
            'host'      => self::dataBase()["DATABASE_SERVER"],
            'database'  => self::dataBase()["DATABASE_DB"],
            'username'  => self::dataBase()["DATABASE_USER"],
            'password'  => self::dataBase()["DATABASE_PASS"],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ], 'crm');

        $this->connection->addConnection([
            'driver'    => 'sqlsrv',
            'host'      => '54.166.100.176,1845',
            'database'  => 'RefosusPruebas',
            'username'  => 'refosus',
            'password'  => 'SAsusweb02**',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ], 'refosus');
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