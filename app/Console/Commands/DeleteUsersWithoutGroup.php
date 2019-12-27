<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class DeleteUsersWithoutGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wichtel:delete-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete User accounts without a wichtel group';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::doesntHave('groups')->delete();

        if ($users > 0) {
            Log::info("Deleted {$users} user accounts without groups.");
        }
    }
}
