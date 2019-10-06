<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusRepresentative extends Migration {

    public function up() {
        Schema::create('denora_duebus_representative', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unique();
            $table->string('number_of_clients')->nullable();
            $table->string('interested_in')->nullable();
            $table->string('business_name')->nullable();
            $table->integer('year_founded')->nullable();
            $table->string('website')->nullable();

            $table->json('social_media');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_representative');
    }

}
