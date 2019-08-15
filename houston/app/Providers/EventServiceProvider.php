<?php

namespace App\Providers;

use App\Media;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NewEvent' => [
            'App\Listeners\SendEventNotification',
            'App\Listeners\SendInvitationReminder'
        ],
        'App\Events\PlayerRegistered' => [
            'App\Listeners\InviteParentToRegister'
        ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\CheckUserInvitations'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
        Event::listen('media.view', function (Media $media) {
            if (!$this->isMediaViewed($media)) {
                $media->increment('views');

                $this->storeView($media);
            }

            $media->update(['last_viewed_at' => Carbon::now()]);
        });

        Event::listen('media.play', function (Media $media) {
            if (!$this->isMediaPlayed($media)) {
                $media->increment('plays');

                $this->storePlay($media);

                return response()->json('media play counted', 200);
            } else {
                return response()->json('media play already counted', 200);
            }
        });

        // handle upload media points system
        Event::listen('add.points.upload_media', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->create([
                'for'    => 'upload_media',
                'points' => config('upload_media'),
            ]);
        });

        // handle add Comment points system
        Event::listen('add.points.add_comment', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->create([
                'for'    => 'add_comment',
                'points' => config('add_comment'),
            ]);
        });

        // handle add like to media points system
        Event::listen('add.points.media_get_like', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->create([
                'for'    => 'media_get_like',
                'points' => config('media_get_like'),
            ]);
        });

        // handle add like to media points system
        Event::listen('add.points.media_remove_like', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->where('for', 'media_get_like')->first()->delete();
        });

        // handle add like to comment points system
        Event::listen('add.points.comment_get_like', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->create([
                'for'    => 'comment_get_like',
                'points' => config('comment_get_like'),
            ]);
        });

        // handle remove like to comment points system
        Event::listen('add.points.comment_remove_like', function ($user_id) {
            $user = User::findOrFail($user_id);

            $user->points()->where('for', 'comment_get_like')->first()->delete();
        });
    }

    private function isMediaViewed($media)
    {
        $viewed = session()->get('viewed_media', []);

        return in_array($media->id, $viewed);
    }

    private function isMediaPlayed($media)
    {
        $played = session()->get('played_media', []);

        return in_array($media->id, $played);
    }

    private function storeView($media)
    {
        session()->push('viewed_media', $media->id);
    }

    private function storePlay($media)
    {
        session()->push('played_media', $media->id);
    }
}
