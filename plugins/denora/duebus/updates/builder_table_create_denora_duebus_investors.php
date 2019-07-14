<?php namespace Denora\Duebus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusInvestors extends Migration
{
    public function up()
    {
        Schema::create('denora_duebus_investors', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('investments_from')->nullable();
            $table->integer('investments_to')->nullable();
            $table->integer('businesses_invested_in');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebus_investors');
    }
}
