<?php

namespace App\Jobs;

use Auth;
use Mail;

use App\Followers;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type, $user, $handler_output, $new_file_name, $original_name, $remote_or_clone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $user, $original_name, $new_file_name, $handler_output = null, $remote_or_clone = null)
    {
        $this->type = $type;
        $this->user = $user;
        $this->handler_output = $handler_output;
        $this->new_file_name = $new_file_name;
        $this->original_name = $original_name;
        $this->remote_or_clone = $remote_or_clone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->type ==  'sendEmailAboutRemoteUploadFinished') {
            $this->sendEmailAboutRemoteUploadFinished($this->user, $this->original_name, $this->new_file_name, $this->remote_or_clone);
        } elseif($this->type ==  'sendEmailAboutNewMediaToFollowers') {
            $this->sendEmailAboutNewMediaToFollowers($this->user, $this->original_name, $this->new_file_name, $this->handler_output);
        } elseif($this->type ==  'sendEmailAboutUploadToAdmin') {
            $this->sendEmailAboutUploadToAdmin($this->user, $this->original_name, $this->new_file_name);
        }
    }

    public function sendEmailAboutRemoteUploadFinished($user, $original_name, $new_file_name, $remote_or_clone)
    {
        if (Auth::id() != 0 and config('sparkpost_secret')) {
            // Send email about new media to people that follow the user
            $email = $user->email;

            $array = [
                'email'           => $email,
                'remote_or_clone' => $remote_or_clone
            ];

            $data = [
                'user'          => $user,
                'original_name' => $original_name,
                'new_file_name' => $new_file_name
            ];

            if (!str_contains($array['email'], request()->getHost())) {
                Mail::send('emails.remote_upload_finished', $data, function ($message) use ($array) {
                    if (config('no_reply_email')) {
                        $message
                            ->from(config('no_reply_email'), config('website_title'))
                            ->to($array['email'])
                            ->subject('Your ' . $array['remote_or_clone'] . ' Upload is finished!');
                    } else {
                        $message
                            ->to($array['email'])
                            ->subject('Your ' . $array['remote_or_clone'] . ' Upload is finished!');
                    }
                });
            }
        }
    }

    public function sendEmailAboutNewMediaToFollowers($user, $original_name, $new_file_name, $handler_output)
    {
        if (config('sparkpost_secret')) {
            // Send email about new media to people that follow the user
            $following_user_ids = Followers::where('follower_id', $user->id)->pluck('user_id')->all();

            foreach ($following_user_ids as $following_user_id) {
                $following_user = User::where('id', $following_user_id)->first();

                // Check if user would like to be notified
                if ($following_user->notification_followers_add_media == 1) {
                    $array = [
                        'email'    => $following_user->email,
                        'username' => $user->username,
                        'type'     => $handler_output['type'],
                    ];

                    $data = [
                        'user'           => $user,
                        'following_user' => $following_user,
                        'type'           => $handler_output['type'],
                        'slug'           => $new_file_name,
                        'title'          => $original_name,
                    ];

                    if ($handler_output['type'] == 'audio') {
                        $data = [
                            'user'           => $user,
                            'following_user' => $following_user,
                            'type'           => $handler_output['type'].' file',
                            'slug'           => $new_file_name,
                            'title'          => $original_name,
                        ];
                    }

                    // if the email includes clooud then don't send email
                    // clooud email is generated for users that sign up through social media
                    // and there email can't get accessed, so we create a new one for them
                    if (! str_contains($array['email'], request()->getHost())) {
                        Mail::send('emails.new_media_by_following_user', $data, function ($message) use ($array) {
                            if (config('no_reply_email')) {
                                $message
                                    ->from(config('no_reply_email'), config('website_title'))
                                    ->to($array['email'])
                                    ->subject($array['username'] . ' uploaded a new ' . $array['type'] . '!');
                            } else {
                                $message
                                    ->to($array['email'])
                                    ->subject($array['username'] . ' uploaded a new ' . $array['type'] . '!');
                            }
                        });
                    }
                }
            }
        }
    }

    public function sendEmailAboutUploadToAdmin($user, $original_name, $new_file_name)
    {
        if (config('sparkpost_secret')) {
            // Send email about new media to admins so they can approve
            if (! $user) {
                $user = User::find(1);
            }

            $data = [
                'user'          => $user,
                'original_name' => $original_name,
                'new_file_name' => $new_file_name
            ];

            if (config('no_reply_email')) {
                Mail::send('emails.notify_admin_about_upload', $data, function ($message) {
                    $message
                        ->from(config('admin_email'), config('website_title'))
                        ->to(config('no_reply_email'))
                        ->subject('Another Upload is finished! Approve it.');
                });
            }
        }
    }
}
