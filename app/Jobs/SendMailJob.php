<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Queueable;

    private User $user;

    private ?Mailable $mailable = null;

    /**
     * Create a new job instance.
     */
    public function __construct(User     $user,
                                Mailable $mailable
    )
    {
        $this->user = $user;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send($this->mailable);
    }
}
