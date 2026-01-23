<?php

declare(strict_types=1);

namespace App\Mail;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SelfRegistrationConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public Group $group;

    public function __construct(User $user, Group $group)
    {
        $this->user = $user;
        $this->group = $group;
    }

    public function build()
    {
        $token = $this->user->approveToken($this->group);
        $approveLink = sprintf('%s/answer?action=approve&token=%s', config('app.url'), $token);
        $declineLink = sprintf('%s/answer?action=decline&token=%s', config('app.url'), $token);

        return $this
            ->subject('Bestätige deine Anmeldung zum Wichteln')
            ->markdown('emails.self-registration-confirm')
            ->with([
                'approveLink' => $approveLink,
                'declineLink' => $declineLink,
            ]);
    }
}
