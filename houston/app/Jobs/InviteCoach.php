<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class InviteCoach implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $team_id;
    public $name;
    public $email;
    public $phone;
    public $prefix;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($team_id, $name, $email, $phone, $prefix)
    {
        $this->team_id = $team_id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->prefix = $prefix;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send invitation
        \Log::info($this->prefix . ' invitation sent');
    }
}
