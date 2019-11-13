<?php namespace Denora\TapCompany\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class BuilderTableCreateDenoraTapcompanyTransactions extends Migration {

    public function up() {

        Schema::create('denora_tapcompany_transactions', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->string('chargeable');
            $table->integer('chargeable_id');
            $table->string('charge_id')->unique();
            $table->text('payment_url');
            $table->integer('price');
            $table->integer('points');
            $table->text('description')->nullable();
            $table->text('redirect_url');

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('denora_tapcompany_transactions');
    }
}
