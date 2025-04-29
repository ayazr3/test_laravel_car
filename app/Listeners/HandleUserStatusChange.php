<?php

namespace App\Listeners;

use App\Events\UserStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class HandleUserStatusChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserStatusChanged $event): void
    {
        if ($event->status === false) {
            Auth::logout();
            $event->user->tokens()->delete();
        }
    }
}
