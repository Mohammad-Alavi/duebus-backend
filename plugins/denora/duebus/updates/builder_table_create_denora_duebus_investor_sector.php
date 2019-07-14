<?php namespace RainLab\User\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateInvestorSectorTable extends Migration {

    public function up() {
        Schema::create('denora_duebus_investor_sector', function ($table) {
            $table->integer('investor_id')->unsigned();
            $table->integer('sector_id')->unsigned();
            $table->primary(['investor_id', 'sector_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_investor_sector');
    }

}
