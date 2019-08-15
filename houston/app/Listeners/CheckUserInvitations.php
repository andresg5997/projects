<?php

namespace App\Listeners;

use App\Invitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckUserInvitations
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  IlluminateAuthEventsRegistered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $email = $event->user->email;
        $invitations = Invitation::where('email', $email)->get();
        foreach($invitations as $invitation) {
            $user->teams()->attach($invitation->team_id);
        }
    }
}
