<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupTooSmall extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Group */
    public $group;

    /**
     * Admin of group
     *
     * @var User
     */
    public $admin;

    /**
     * Create a new message instance.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
        $this->admin = $group->admin();
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
            'token' => $this->admin->api_token,
        ]);
        return $this
            ->subject('Zu wenig Teilnehmer')
            ->markdown('emails.group-too-small')
            ->with([
                'linkToGroup' => $linkToGroup,
            ]);
    }
}
