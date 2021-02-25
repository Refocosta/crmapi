<?php namespace App\Controllers;
use Core\Base;
class TablesController extends Base
{
    public function tables()
    {
        $this->DB()::schema('crm')->create('Channels', function ($table) {
            $table->increments('Id');
            $table->string('Name');
            $table->integer('Status');
            $table->timestamps();
        });
    }
}