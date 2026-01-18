<?php

namespace App\Console\Commands;

use App\Group;
use App\Mail\GroupDeleted;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DeleteOldGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wichtel:delete-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all groups with users, which are informed about deletion';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        collect(Group::with('users')->forDeletion()->get())->each(static function (Group $group) {
            Mail::to($group->admin())->send(new GroupDeleted($group->admin(), $group));
            $group->delete();
        });
    }
}
