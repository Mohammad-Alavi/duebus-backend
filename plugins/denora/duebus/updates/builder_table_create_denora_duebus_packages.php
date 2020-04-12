<?php namespace Denora\Duebus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusPackages extends Migration
{
    public function up()
    {
        Schema::create('denora_duebus_packages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->double('amount', 10, 0);
            $table->double('offer', 10, 0);
            $table->integer('points');
            $table->boolean('recommended')->default(0);
            $table->integer('validity_months')->default(0);
            $table->integer('validity_days')->default(0);
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denora_duebus_packages');
    }
}
