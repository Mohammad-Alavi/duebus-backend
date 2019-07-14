<?php namespace Denora\Duebus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusExperiences extends Migration
{
    public function up()
    {
        Schema::create('denora_duebus_experiences', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id');
            $table->string('company');
            $table->string('job_title');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebus_experiences');
    }
}
