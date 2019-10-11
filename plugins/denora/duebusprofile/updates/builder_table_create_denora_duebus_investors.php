<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusInvestors extends Migration {

    public function up() {
        Schema::create('denora_duebus_investors', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('range_of_businesses_invested_in')->nullable();
            $table->string('range_of_investment')->nullable();
            $table->integer('user_id')->unique();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_investors');
    }

}
