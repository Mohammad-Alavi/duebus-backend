<?php namespace Denora\Duebusbusiness\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusbusinessEquityholder extends Migration
{
    public function up()
    {
        Schema::create('denora_duebusbusiness_equityholder', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('business_id');
            $table->string('name');
            $table->double('equity', 10, 0);
            $table->string('email')->nullable();
            $table->string('role');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebusbusiness_equityholder');
    }
}
