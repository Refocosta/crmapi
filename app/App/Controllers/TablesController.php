<?php namespace App\Controllers;
use Exceptions\QueryException;
use Core\Base;
final class TablesController extends Base
{
    public function tables()
    {
        try {
            // CONTACTS  //
            $this->DB()::schema('crm')->create('Contacts', function ($table) {
                $table->increments('Id');
                $table->string('Name', 55);
                $table->string('Cellphone', 15);
                $table->string('Email', 125);
                $table->text('Petition');
                $table->tinyInteger('Status');
                $table->timestamps();
            });
            // CHANNELS //
            $this->DB()::schema('crm')->create('Channels', function ($table) {
                $table->increments('Id');
                $table->string('Name');
                $table->tinyInteger('Status');
                $table->timestamps();
            });
            // CONTACTS CHANNELS //
            $this->DB()::schema('crm')->create('ContactsChannels', function ($table) {
                $table->increments('Id');
                $table->integer('ContactsId')->unsigned();
                $table->integer('ChannelsId')->unsigned();
                $table->foreign('ContactsId') 
                    ->references('Id')
                    ->on('Contacts')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('ChannelsId')
                    ->references('Id')
                    ->on('Channels')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
            // TYPES CHANNELS //
            $this->DB()::schema('crm')->create('TypesChannels', function ($table) {
                $table->increments('Id');
                $table->string('Name');
                $table->integer('ChannelsId');
                $table->tinyInteger('Status');
                $table->timestamps();
                $table->foreign('ChannelsId')
                    ->references('Id')
                    ->on('Channels')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            throw new QueryException($e->getMessage(), 500);
        }
    }

    public function down()
    {
        try {
            $this->DB()::schema('crm')->dropIfExists('ContactsChannels');
            $this->DB()::schema('crm')->dropIfExists('Contacts');
            $this->DB()::schema('crm')->dropIfExists('TypesChannels');
            $this->DB()::schema('crm')->dropIfExists('Channels');
        } catch (\Illuminate\Database\QueryException $e) {
            throw new QueryException($e->getMessage(), 500);
        }
    }
}