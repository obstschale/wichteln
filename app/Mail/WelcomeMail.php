<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{

    use Queueable, SerializesModels;

    /**
     * New user to welcome.
     *
     * @var User
     */
    public $user;

    /**
     * New Group
     *
     * @var Group
     */
    public $group;


    /**
     * Create a new message instance.
     *
     * @param User  $user
     * @param Group $group
     */
    public function __construct(User $user, Group $group)
    {
        $this->user  = $user;
        $this->group = $group;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $linkToGroup = route('wichtelgroup', [
            'group' => $this->group->id,
            'token' => $this->user->api_token
        ]);

        return $this->subject("Willkommen bei Wichtel.me")->markdown('emails.welcome')->with([
                'linkToGroup' => $linkToGroup
            ]);
    }
}
