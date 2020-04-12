<?php namespace Denora\Duebus\Updates;

use Denora\Duebus\Models\Package;
use October\Rain\Database\Updates\Seeder;

class SeedPackagesTable extends Seeder {

    public function run() {
        Package::create([
            'name' => 'Starter',
            'description' => '',
            'amount' => 49,
            'offer' => 19,
            'points' => 49,
            'recommended' => true,
            'validity_months' => 1,
            'validity_days' => 15,
        ]);
        Package::create([
            'name' => 'Executive',
            'description' => '',
            'amount' => 100,
            'offer' => 25,
            'points' => 75,
            'recommended' => false,
            'validity_months' => 3,
            'validity_days' => 0,
        ]);
    }

}
