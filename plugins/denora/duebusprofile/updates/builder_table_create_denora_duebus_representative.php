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
            $table->json('interested_in')->nullable();
            $table->string('range_of_investment')->nullable();
            $table->string('business_name')->nullable();
            $table->date('year_founded')->nullable();
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
