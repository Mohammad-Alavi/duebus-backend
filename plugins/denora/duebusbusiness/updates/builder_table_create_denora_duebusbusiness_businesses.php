<?php namespace Denora\Duebusbusiness\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraDuebusbusinessBusinesses extends Migration {
    public function up() {
        Schema::create('denora_duebusbusiness_businesses', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('entrepreneur_id');
            $table->string('name');
            $table->string('business_brief');
            $table->string('industry');
            $table->date('year_founded');
            $table->string('website')->nullable();
            $table->boolean('allow_reveal');
            $table->boolean('existing_business');
            $table->boolean('has_commercial_license');
            $table->json('jurisdiction_of_commercial_license');
            $table->string('legal_structure')->nullable();
            $table->string('your_role_in_business');
            $table->string('reason_of_selling_equity');
            $table->double('business_value');
            $table->double('equity_for_sale');
            $table->double('asking_price');
            $table->boolean('is_involved_in_any_proceedings')->nullable();
            $table->string('is_involved_in_any_proceedings_description')->nullable();
            $table->boolean('is_concern_with_business_employees')->nullable();
            $table->string('is_concern_with_business_employees_description')->nullable();
            $table->boolean('is_founder_or_holder_in_debt')->nullable();
            $table->string('is_founder_or_holder_in_debt_description')->nullable();
            $table->json('three_years_statement');
            $table->json('social_media');
            $table->json('equity_holders');
            $table->boolean('is_published');

            $table->string('promotion_industry')->nullable();
            $table->timestamp('promotion_expire_date')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_duebusbusiness_businesses');
    }
}
