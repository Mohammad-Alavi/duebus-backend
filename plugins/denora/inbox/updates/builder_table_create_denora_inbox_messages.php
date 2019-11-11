<?php namespace Denora\Inbox\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraInboxMessages extends Migration
{
    public function up()
    {
        Schema::create('denora_inbox_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('session_id');
            $table->text('title')->nullable();
            $table->text('text');
            $table->boolean('is_read');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denora_inbox_messages');
    }
}
