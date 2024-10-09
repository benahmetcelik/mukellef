<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionRenewedMail;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\UserSubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'a';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        Mail::to($user->email)->send(new SubscriptionRenewedMail());
    }
}
