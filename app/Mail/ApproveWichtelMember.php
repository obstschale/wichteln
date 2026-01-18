<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ApproveWichtelMember extends Mailable
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
     * @param User  $user
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
        $token = Str::random(16);
        $this->user->saveApproveToken($this->group, $token);
        $link = sprintf('%s/token?action=approve&token=%s', config('app.url'), $token);

        return $this
            ->subject('Du wurdest zum Wichteln eingeladen')
            ->markdown('emails.approve')
            ->with([
                'link' => $link,
                'admin' => $this->group->admin(),
            ]);
    }
}
