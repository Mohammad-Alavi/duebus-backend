<?php namespace Denora\Duebus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusEducations extends Migration
{
    public function up()
    {
        Schema::create('denora_duebus_educations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('school');
            $table->string('field_of_study');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->integer('entrepreneur_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebus_educations');
    }
}
