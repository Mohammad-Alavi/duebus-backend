<?php namespace Denora\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraBlogCategories extends Migration
{
    public function up()
    {
        Schema::create('denora_blog_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('label');
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_blog_categories');
    }
}
