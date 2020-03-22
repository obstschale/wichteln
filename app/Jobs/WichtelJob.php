<?php

namespace App\Jobs;

use App\Group;
use App\Mail\GroupTooSmall;
use App\Statistic;
use Illuminate\Bus\Queueable;
use App\Mail\WichtelBuddyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WichtelJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Group instance.
     *
     * @var Group
     */
    protected $group;

    /**
     * Create a new job instance.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $approvedUsers = $this->group->approvedUsers->shuffle();

        // Send Mail to Admin if Group too small
        if ($approvedUsers->count() <= 2) {
            $this->group->status = 'created';
            $this->group->save();
            Mail::to($this->group->admin())->queue(new GroupTooSmall($this->group));
            return;
        }

        $approvedUsers->map(function ($item, $key) use ($approvedUsers) {
            $buddy = ($key + 1 === $approvedUsers->count()) ? 0 : $key + 1;
            $item->saveBuddy($this->group, $approvedUsers[$buddy]->id);

            Mail::to($item)->queue(new WichtelBuddyMail($item, $this->group));

            return $item;
        });

        Statistic::groupStarted();
    }
}
