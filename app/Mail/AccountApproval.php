<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Website;

class AccountApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $website;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Website $website)
    {
        $this->user = $user;
        $this->website = $website;
        $this->loginUrl = 'http://' . $website->domain . '/login';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Account Has Been Approved')
                    ->view('emails.account-approval')
                    ->with([
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'websiteName' => $this->website->name,
                        'websiteDomain' => $this->website->domain,
                        'loginUrl' => $this->loginUrl,
                    ]);
    }
}
