<?php namespace Denora\Inbox\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraInboxSessions extends Migration
{
    public function up()
    {
        Schema::create('denora_inbox_sessions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('business_id');
            $table->string('type');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_inbox_sessions');
    }
}
