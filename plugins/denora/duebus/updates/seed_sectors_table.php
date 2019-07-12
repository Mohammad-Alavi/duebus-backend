<?php namespace Denora\Duebus\Updates;

use Denora\Duebus\Models\Sector;
use October\Rain\Database\Updates\Seeder;

class SeedSectorsTable extends Seeder {

    public function run() {
        Sector::create(['label' => 'Belbool']);
        Sector::create(['label' => 'Shantool']);
        Sector::create(['label' => 'Hantoosh']);
    }

}
