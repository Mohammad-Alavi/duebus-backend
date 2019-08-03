<?php namespace Denora\Duebusbusiness\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusbusinessBusinesses extends Migration {
    public function up() {
        Schema::create('denora_duebusbusiness_businesses', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('industry');
            $table->integer('year_founded');
            $table->string('website')->nullable();
            $table->boolean('allow_reveal');
            $table->boolean('existing_business');
            $table->string('legal_structure');
            $table->string('your_role_in_business');
            $table->string('reason_of_selling_equity');
            $table->double('business_value');
            $table->double('equity_for_sale');
            $table->boolean('is_involved_in_any_proceedings');
            $table->boolean('is_concern_with_business_employees');
            $table->boolean('is_founder_or_holder_in_debt');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebusbusiness_businesses');
    }
}
