<?php namespace Denora\Duebusbusiness\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusbusinessSocialmedias extends Migration {

    public function up() {
        Schema::create('denora_duebusbusiness_socialmedias', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('business_id');
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebusbusiness_socialmedias');
    }

}
