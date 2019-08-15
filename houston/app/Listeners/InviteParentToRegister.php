<?php

namespace App\Listeners;

use App\Events\PlayerRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class InviteParentToRegister
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
     * @param  PlayerRegistered  $event
     * @return void
     */
    public function handle(PlayerRegistered $event)
    {
        $data =
            [
                'player' => $event->player,
                'team' => $event->player->team,
                'email' => $event->player->parent_email
            ];

        $mail = Mail::send('emails.invite_parent', $data, function ($message) use ($data) {
            $message
                ->from(env('MAIL_USERNAME'), config('website_title'))
                ->to($data['email'])
                ->subject("Your kid {$data['player']->name} was registered in " . config('website_title'));
        });
        return;
    }
}
