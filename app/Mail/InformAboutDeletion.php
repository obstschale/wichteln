<?php

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformAboutDeletion extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User  */
    public $user;

    /** @var Group */
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
        return $this->subject('Wichtelgruppe wird gelöscht')
                    ->markdown('emails.inform-deletion');
    }
}
