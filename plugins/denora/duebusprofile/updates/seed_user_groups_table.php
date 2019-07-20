<?php namespace Denora\Duebusprofile\Updates;

use October\Rain\Database\Updates\Seeder;
use RainLab\User\Models\UserGroup;

class SeedUserGroupsTable extends Seeder {

    public function run() {
        UserGroup::query()->delete();

        UserGroup::create([
            'name'        => 'Admin',
            'code'        => 'admin',
            'description' => 'A group for admins.'
        ]);
    }

}
