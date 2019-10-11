<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateRepresentativeSectorTable extends Migration {

    public function up() {
        Schema::create('denora_duebus_br_sector', function ($table) {
            $table->integer('representative_id')->unsigned();
            $table->integer('sector_id')->unsigned();
            $table->primary(['representative_id', 'sector_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_br_sector');
    }

}
