<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMemberMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * New group member
     *
     * @var User
     */
    public $user;

    /**
     * Wichtelgroup
     *
     * @var Group
     */
    public $group;

    /**
     * Create a new message instance.
     *
     * @param User   $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->group = Group::whereHas('users', function ($group) use ($user) {
            $group->where('id', $user->id);
        })->first();
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
        return $this->subject('Willkommen bei Wichtel.me')->markdown('emails.welcomeMember')->with([
            'linkToGroup' => $linkToGroup
        ]);
    }
}
