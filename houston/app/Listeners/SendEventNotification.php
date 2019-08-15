<?php

namespace App\Listeners;

use App\Events\NewEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEventNotification
{
    public $event;

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
     * @param  NewEvent  $event
     * @return void
     */
    public function handle(NewEvent $event)
    {
        $this->event = $event->event;
        $count = 0;
        $team = $event->teams[0];
        foreach($team->players as $player){
            if($this->sendMail($player, $team)) {
                $count++;
            }
        }
        \Log::info($count . ' emails sent.');
        return;
    }

    public function sendMail($player, $team)
    {
        $data = [
            'email' => $player->parent_email,
            'event' => $this->event,
            'player' => $player,
            'team'  => $team
        ];

        $mail = Mail::send('emails.new_event', $data, function ($message) use ($data) {
            $message
                ->from(env('MAIL_USERNAME'), config('website_title'))
                ->to($data['email'])
                ->subject("New Event: {$data->event->name} on " . date('M d, h:i A', strtotime($event->date)));
        });
        if($mail){
            return true;
        }
        return false;
    }
}
