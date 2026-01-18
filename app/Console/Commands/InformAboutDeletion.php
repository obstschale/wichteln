<?php

namespace App\Console\Commands;

use App\Group;
use Illuminate\Console\Command;

class InformAboutDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wichtel:inform-deletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inform group admin about auto deletion of group';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredGroups = Group::with('users')
            ->created()
            ->notInformed()
            ->olderThan(30, 'days')
            ->dateReached()
            ->get();
        $finishedGroups = Group::with('users')
            ->started()
            ->notInformed()
            ->olderThan(30, 'days')
            ->get();

        $expiredGroups
            ->merge($finishedGroups)
            ->each(static function (Group $group) {
                $group->informAboutDeletion();
            });
    }
}
