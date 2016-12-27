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

                for ($i = 0; $i < 10; $i++) {
                    $status = [
                        'invited',
                        'approved',
                        'declined',
                    ];

                    $pivotData = [
                        'status' => $status[array_rand($status)],
                        'wishlist' => str_random(100),
                        'is_admin' => ($i === 0) ? true : false,
                    ];

                    $g->users()->save(
                        factory(App\User::class)->make(),
                        $pivotData
                    );
                }
            });
    }
}
