<?php

namespace App\Console\Commands;

use App\Statistic;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CountAccounts extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wichtel:count-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count accounts for statistics';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $accounts  = Statistic::accounts();
        $updatedAt = $accounts->updated_at;

        $newAccounts = User::where('created_at', '>', $updatedAt)->count();

        if (Carbon::now()->isSameMonth($updatedAt)) {
            $accounts->count += $newAccounts;
            $accounts->save();

            return;
        }

        $accounts = new Statistic([
            'name'  => Statistic::ACCOUNTS,
            'count' => $newAccounts,
        ]);
        $accounts->save();
    }
}
