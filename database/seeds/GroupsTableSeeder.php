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
                        'wishlist' => generateRandomString(100),
                    ];

                    $g->users()->save(
                        factory(App\User::class)->make(),
                        $pivotData
                    );
                }
            });
    }
}
