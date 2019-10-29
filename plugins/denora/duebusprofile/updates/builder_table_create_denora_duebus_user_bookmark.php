<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateUserBookmarkTable extends Migration {

    public function up() {
        Schema::create('denora_duebus_user_bookmark', function ($table) {
            $table->integer('user_id')->unsigned();
            $table->integer('business_id')->unsigned();
            $table->primary(['user_id', 'business_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebus_user_bookmark');
    }

}
