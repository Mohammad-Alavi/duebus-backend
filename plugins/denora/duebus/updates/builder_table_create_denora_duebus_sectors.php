<?php namespace Denora\Duebus\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusSectors extends Migration {
    public function up() {
        Schema::create('denora_duebus_sectors', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('label');
            $table->integer('sort_order')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_sectors');
    }
}
