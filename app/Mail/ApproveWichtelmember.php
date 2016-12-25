<?php

namespace App\Mail;

use App\Group;
use App\User;
use App\UserToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveWichtelmember extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance the mail to sent to.
     *
     * @var User
     */
    public $user;

    /**
     * The group instance of member.
     *
     * @var Group
     */
    public $group;

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
        // Create approve link
        $token = str_random(16);
        $this->user->saveApproveToken($this->group, $token);
        $link = sprintf('%s/token?action=approve&token=%s', env('APP_URL'), $token);

        return $this->view('emails.approve')->with([
            'link' => $link,
        ]);
    }
}
