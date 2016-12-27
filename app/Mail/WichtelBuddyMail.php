<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WichtelBuddyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The group instance.
     *
     * @var Group
     */
    public $group;

    /**
     * The user instance.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Group $group
     */
    public function __construct(User $user, Group $group)
    {
        $this->user = $user;
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user->load(['groups' => function ($query) {
            $query->where('group_id', $this->group->id);
        }]);

        $buddy_id = $this->user->groups[0]->pivot->buddy_id;

        $buddy = User::where('id', $buddy_id)->with(['groups' => function ($query) {
            $query->where('group_id', $this->group->id);
        }])->first();

        return $this->view('emails.buddy')->with([
            'buddy' => $buddy,
        ]);
    }
}
