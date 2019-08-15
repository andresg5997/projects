<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendTeamInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $team;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $team)
    {
        $this->data = $data;
        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $data['team'] = $this->team;
        $mail = Mail::send('emails.team_invitation', $data, function ($message) use ($data) {
            $message
                ->from(env('MAIL_USERNAME'), config('website_title'))
                ->to($data['email'])
                ->subject("Congrats! You were invited to join the team {$data['team']->name} dashboard on " . config('website_title') . '!');
        });
        return;
    }
}
