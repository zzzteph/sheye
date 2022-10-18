<?php

namespace App\Mail;
use App\Models\User;
use App\Models\Scope;
use App\Models\Resource;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainsFound extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
	 protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('mailgun@shrewdeye.app', 'Resources found')->markdown('emails.domains')->with(['scopes' => $this->user->scopes]);
    }
}
