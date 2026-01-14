<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Website;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $website;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("We've Received Your Registration")
                    ->view('emails.registration-confirmation')
                    ->with([
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'websiteName' => $this->website->name,
                        'websiteDomain' => $this->website->domain,
                    ]);
    }
}
