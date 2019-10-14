<?php namespace Denora\Duebusverification\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusverificationItems extends Migration
{
    public function up()
    {
        Schema::create('denora_duebusverification_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('description')->nullable();
            $table->boolean('is_verified')->nullable()->default(0);
            $table->integer('itemable_id');
            $table->string('itemable_type');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_duebusverification_items');
    }
}
