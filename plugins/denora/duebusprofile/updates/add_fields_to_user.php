<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddFieldsToUser extends Migration {

    public function up() {
        Schema::table('users', function ($table) {

            $table->integer('point')->default(0);
            $table->timestamp('point_expires_at')->nullable();

        });
    }

    public function down() {

        if (Schema::hasColumn('users', 'point')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('point');
            });
        }
        if (Schema::hasColumn('users', 'point_expires_at')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('point_expires_at');
            });
        }
    }

}
