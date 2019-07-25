<?php namespace Denora\Duebus\Updates;

use Denora\Faq\Models\Faq;
use October\Rain\Database\Updates\Seeder;

class SeedFaqsTable extends Seeder {

    public function run() {
        Faq::create(['question' => 'Question 1', 'answer' => '<p>Answer 1 has a <strong>BOLD</strong> part.</p>']);
        Faq::create(['question' => 'Question 2', 'answer' => '<p>Answer 2 has an <i>ITALIC</i> part.</p>']);
        Faq::create(['question' => 'Question 3', 'answer' => '<p>Answer 3 has an <u>UNDERLINED</u> part.</p>']);
    }

}
