<?php namespace Denora\Notification\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraNotificationNotifications extends Migration
{
    public function up()
    {
        Schema::create('denora_notification_notifications', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('action_type');
            $table->string('doer_type')->nullable();
            $table->integer('doer_id')->nullable();
            $table->string('object_type')->nullable();
            $table->integer('object_id')->nullable();
            $table->integer('cost')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_notification_notifications');
    }
}
