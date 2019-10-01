<?php namespace Denora\Duebus\Updates;

use Denora\Blog\Models\Post;
use October\Rain\Database\Updates\Seeder;

class SeedBlogTable extends Seeder {

    public function run() {
        for ($i = 1; $i <= 30; $i++) {

            Post::create([
                'title' => 'Post number ' . $i,
                'text'  => '<p>This is your first ever <strong>blog post<\/strong>! It might be a good idea to update this post with some more relevant content.<\/p>\n<p>You can edit this content by selecting <strong>Blog<\/strong> from the administration back-end menu.<\/p>\n<p><em>Enjoy the good times!<\/em><\/p>',
            ]);
        }
    }

}
