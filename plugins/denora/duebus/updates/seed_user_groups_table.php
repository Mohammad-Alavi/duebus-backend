<?php namespace Denora\Duebus\Updates;

use RainLab\User\Models\UserGroup;
use October\Rain\Database\Updates\Seeder;

class SeedUserGroupsTable extends Seeder
{
    public function run()
    {
        UserGroup::query()->delete();

        UserGroup::create([
            'name' => 'Admin',
            'code' => 'admin',
            'description' => 'A group for admins.'
        ]);
    }
}
