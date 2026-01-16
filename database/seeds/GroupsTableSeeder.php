<?php

use App\Group;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::factory()
            ->count(5)
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
                        'wishlist' => Str::random(100),
                        'is_admin' => ($i === 0) ? true : false,
                    ];

                    $g->users()->save(
                        User::factory()->make(),
                        $pivotData
                    );
                }
            });
    }
}
