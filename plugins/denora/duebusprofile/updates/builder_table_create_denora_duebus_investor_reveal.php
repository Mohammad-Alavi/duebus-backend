<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateInvestorRevealTable extends Migration {

    public function up() {
        Schema::create('denora_duebus_investor_reveal', function ($table) {
            $table->integer('investor_id')->unsigned();
            $table->integer('business_id')->unsigned();
            $table->primary(['investor_id', 'business_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_investor_reveal');
    }

}
