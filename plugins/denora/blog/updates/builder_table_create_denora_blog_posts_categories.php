<?php namespace Denora\Blog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraBlogPostsCategories extends Migration
{
    public function up()
    {
        Schema::create('denora_blog_posts_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('category_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('denora_blog_posts_categories');
    }
}
