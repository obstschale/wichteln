<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Group::class, 5)
            ->create()
            ->each(function ($g) {
                $g->users()->saveMany(factory(App\User::class, 10)->make());
            });
    }
}
