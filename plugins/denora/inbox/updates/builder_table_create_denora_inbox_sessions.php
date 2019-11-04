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
            $table->boolean('is_read_by_sender');
            $table->boolean('is_read_by_receiver');

            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_inbox_sessions');
    }
}
