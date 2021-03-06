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
    public $admin;

    /** @var Group */
    public $group;


    /**
     * Create a new message instance.
     *
     * @param User  $admin
     * @param Group $group
     */
    public function __construct(User $admin, Group $group)
    {
        $this->admin = $admin;
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('Wichtelgruppe wird gelöscht');

        if ($this->group->started()) {
            $mail = $mail->markdown('emails.inform-deletion-finished');
        } else {
            $mail = $mail->markdown('emails.inform-deletion-expired');
        }

        return $mail;
    }
}
