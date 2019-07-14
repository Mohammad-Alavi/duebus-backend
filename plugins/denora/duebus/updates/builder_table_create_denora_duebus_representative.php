<?php namespace Denora\Duebus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusRepresentative extends Migration
{
    public function up()
    {
        Schema::create('denora_duebus_representative', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebus_representative');
    }
}
