<?php namespace Denora\Duebusverification\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusverificationChamberOfCommerces extends Migration
{
    public function up()
    {
        Schema::create('denora_duebusverification_chamber_of_commerces', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('business_verification_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denora_duebusverification_chamber_of_commerces');
    }
}
