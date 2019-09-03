<?php namespace Denora\Duebus\Updates;

use Carbon\Carbon;
use October\Rain\Database\Updates\Seeder;
use RainLab\Blog\Models\Post;

class SeedBlogTable extends Seeder {

    public function run() {
        for ($i = 1; $i <= 30; $i++){
            Post::create([
                'title' => 'Post number ' . $i,
                'slug' => 'slug-test-' . $i,
                'content' => 'This is your first ever **blog post**! It might be a good idea to update this post with some more relevant content.\n\nYou can edit this content by selecting **Blog** from the administration back-end menu.\n\n*Enjoy the good times!*',
                'content_html' => '<p>This is your first ever <strong>blog post<\/strong>! It might be a good idea to update this post with some more relevant content.<\/p>\n<p>You can edit this content by selecting <strong>Blog<\/strong> from the administration back-end menu.<\/p>\n<p><em>Enjoy the good times!<\/em><\/p>',
                'published_at' => Carbon::now(),
                'published' => 1
            ]);
        }
    }

}
