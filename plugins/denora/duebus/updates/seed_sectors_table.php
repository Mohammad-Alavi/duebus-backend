<?php namespace Denora\Duebus\Updates;

use Denora\Duebus\Models\Sector;
use October\Rain\Database\Updates\Seeder;

class SeedSectorsTable extends Seeder {

    public function run() {
        Sector::create(['label' => 'Tech']);
        Sector::create(['label' => 'Food']);
        Sector::create(['label' => 'Beauty']);
        Sector::create(['label' => 'Services']);
    }

}
