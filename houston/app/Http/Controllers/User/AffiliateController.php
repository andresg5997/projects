<?php

namespace App\Http\Controllers\User;

use App\Affiliate;
use App\AffiliateAudioView;
use App\AffiliateImageView;
use App\AffiliateVideoView;
use App\Http\Controllers\Controller;
use App\Page;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AffiliateController extends Controller
{
    public function settings(Request $request)
    {
        $page = 'settings';
        $user = $request->user();

        $status_ask = Affiliate::where('user_id', $user->id)->where('adblock_ask', 1)->first();
        $status_off = Affiliate::where('user_id', $user->id)->where('adblock_off', 1)->first();

        return view('affiliate.settings', compact('page', 'user', 'status_ask', 'status_off'));
    }

    public function updateSettings(Request $request)
    {
        if ($request->input('adblock-ask') !== null) {
            $adblock_ask = 1;
        } else {
            $adblock_ask = 0;
        }

        if ($request->input('adblock-off') !== null) {
            $adblock_off = 1;
        } else {
            $adblock_off = 0;
        }

        $user_id = $request->user()->id;

        $affiliate = new Affiliate();
        $affiliate->where('user_id', $user_id)->first()->update([
            'adblock_ask' => $adblock_ask,
            'adblock_off' => $adblock_off,
        ]);

        flash('Your affiliate settings have been updated!', 'success');

        return back();
    }

    public function info(Request $request)
    {
        $page = 'info';
        $user = $request->user();

        $tos_parent_id = Page::where('slug', 'tos')->first()->parent;
        $tos_parent_slug = Page::where('id', $tos_parent_id)->first()->slug;

        $cp_parent_id = Page::where('slug', 'copyright-policy')->first()->parent;
        $cp_parent_slug = Page::where('id', $cp_parent_id)->first()->slug;

        return view('affiliate.info', compact('page', 'user', 'tos_parent_slug', 'cp_parent_slug'));
    }

    public function statistics(Request $request)
    {
        $page = 'statistics';
        $user = $request->user();
        $user_id = $user->id;
        $expires_at_interval = config('expires_at_interval');
        $expires_at = Carbon::now()->addMinutes($expires_at_interval);

        // Getting Video Stats Array and Variable Caching
        $video_stats = $this->getVideoStats($user_id, $user, $expires_at, $expires_at_interval);
        $video_stats = $video_stats[0];

        // Getting Image Stats Array and Variable Caching
        $image_stats = $this->getImageStats($user_id, $user, $expires_at, $expires_at_interval);
        $image_stats = $image_stats[0];

        // Getting Audio Stats Array and Variable Caching
        $audio_stats = $this->getAudioStats($user_id, $user, $expires_at, $expires_at_interval);
        $audio_stats = $audio_stats[0];

        $current_account_balance = $this->trimTrailingZeroes($user->current_account_balance);
        $all_time_account_balance = $this->trimTrailingZeroes($user->all_time_account_balance);

        $total_todays_revenue = $this->trimTrailingZeroes($video_stats['todays_revenue'] + $audio_stats['todays_revenue'] + $image_stats['todays_revenue']);
        $total_yesterdays_revenue = $this->trimTrailingZeroes($video_stats['yesterdays_revenue'] + $audio_stats['yesterdays_revenue'] + $image_stats['yesterdays_revenue']);
        $total_last_7_days_revenue = $this->trimTrailingZeroes($video_stats['last_7_days_revenue'] + $audio_stats['last_7_days_revenue'] + $image_stats['last_7_days_revenue']);
        $total_last_30_days_revenue = $this->trimTrailingZeroes($video_stats['last_30_days_revenue'] + $audio_stats['last_30_days_revenue'] + $image_stats['last_30_days_revenue']);
        $total_revenue_this_year = $this->trimTrailingZeroes($video_stats['this_years_revenue'] + $audio_stats['this_years_revenue'] + $image_stats['this_years_revenue']);

        $total_todays_views = $video_stats['todays_views'] + $audio_stats['todays_views'] + $image_stats['todays_views'];
        $total_yesterdays_views = $video_stats['yesterdays_views'] + $audio_stats['yesterdays_views'] + $image_stats['yesterdays_views'];
        $total_last_7_days_views = $video_stats['last_7_days_views'] + $audio_stats['last_7_days_views'] + $image_stats['last_7_days_views'];
        $total_last_30_days_views = $video_stats['last_30_days_views'] + $audio_stats['last_30_days_views'] + $image_stats['last_30_days_views'];
        $total_views_this_year = $video_stats['this_years_views'] + $audio_stats['this_years_views'] + $image_stats['this_years_views'];

        $affiliate_id = User::where('id', $user_id)->first()->affiliate_id;
        $referrals = User::where('referred_by', $affiliate_id)->get();
        $total_referral_amount = array_sum(User::where('referred_by', $affiliate_id)->pluck('all_time_account_balance')->toArray());

        $faq_parent_id = Page::where('slug', 'faq')->first()->parent;
        $faq_parent_slug = Page::where('id', $faq_parent_id)->first()->slug;

        return view('affiliate.statistics.overview',
            compact(
                'page',
                'user',
                'expires_at_interval',
                'current_account_balance',
                'all_time_account_balance',
                'image_stats',
                'audio_stats',
                'video_stats',
                'total_todays_revenue',
                'total_yesterdays_revenue',
                'total_last_7_days_revenue',
                'total_last_30_days_revenue',
                'total_revenue_this_year',
                'total_expires_at_interval',
                'total_todays_views',
                'total_yesterdays_views',
                'total_last_7_days_views',
                'total_last_30_days_views',
                'total_views_this_year',
                'referrals',
                'total_referral_amount',
                'faq_parent_slug'
            )
        );
    }

    public function trimTrailingZeroes($str)
    {
        return preg_replace('/(\.[0-9]+?)0*$/', '$1', number_format($str, 10));
    }

    public function getVideoStats($user_id, $user, $expires_at, $expires_at_interval)
    {
        $prefix_video = 'video';
        $this_months_revenue_video = [];
        $this_months_revenue_video_key = "$prefix_video.this_months_revenue_$user_id";
        if (Cache::has($this_months_revenue_video_key)) {
            $this_months_revenue_video = Cache::get($this_months_revenue_video_key);

            $last_update = new Carbon($this_months_revenue_video['last_update']);
            $now = Carbon::now();
            $diff_in_days = $last_update->diffInDays($now);
            $diff_in_minutes = $last_update->diffInMinutes($now);

            // updating of the arrays
            // if there is 1 or more day difference from when the array was last updated, then shift the array
            if ($diff_in_days >= 1 && $diff_in_days < 30) {
                // shift array however many days difference there was
                for ($x = 0; $x < $diff_in_days; $x++) {
                    for ($i = 0; $i < 30; $i++) {
                        $this_months_revenue_video[$i + 1] = $this_months_revenue_video[$i];
                    }
                }

                // echo "update because $diff_in_days since last update";
                // add however many days are different stats to the array again to get an updated statistics
                for ($i = 0; $i < $diff_in_days; $i++) {
                    $this_months_revenue_video[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }
                // this key stores the last time the array was updated
                $this_months_revenue_video['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_video_key, $this_months_revenue_video);

                // update the whole array
            } elseif ($diff_in_days > 29) {
                for ($i = 0; $i < 30; $i++) {
                    $this_months_revenue_video[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }

                $this_months_revenue_video['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_video_key, $this_months_revenue_video, $expires_at);

                // else update today's values if the last time updated is more than $expires_at_interval minutes
            } elseif ($diff_in_minutes >= $expires_at_interval) {
                // echo "update because $diff_in_minutes >= $expires_at_interval";
                // add today's stats to the array again to get an updated array
                $this_months_revenue_video[0] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(0)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays(0)->endOfDay())
                    ->pluck('commission')
                    ->toArray());

                // this key stores the last time the array was updated
                $this_months_revenue_video['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_video_key, $this_months_revenue_video);
            }
            // echo "no update because diff_in_days $diff_in_days and diffinminutes $diff_in_minutes";
        } else {
            // echo "no cache is present";
            // If no cache is present
            // Revenue today, the past 7 days, and 30 days stored in one array
            for ($i = 0; $i < 30; $i++) {
                $this_months_revenue_video[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->pluck('commission')
                    ->toArray());
            }
            $this_months_revenue_video['last_update'] = Carbon::now();
            Cache::forever($this_months_revenue_video_key, $this_months_revenue_video, $expires_at);
        }

        $this_years_views_video_key = "$prefix_video.this_years_views_$user_id";
        if (Cache::has($this_years_views_video_key)) {
            $this_years_views_video = Cache::get($this_years_views_video_key);
        } else {
            $this_years_views_video = AffiliateVideoView::where('owner_id', $user->id)
                ->where('created_at', '>=', Carbon::parse('first day of January'))
                ->where('created_at', '<=', Carbon::parse('first day of January next year')->subSecond())
                ->count();
            Cache::put($this_years_views_video_key, $this_years_views_video, $expires_at);
        }

        $todays_revenue_video = $this->trimTrailingZeroes($this_months_revenue_video[0]);
        $yesterdays_revenue_video = $this->trimTrailingZeroes($this_months_revenue_video[1]);
        $last_7_days_revenue_video = $this->trimTrailingZeroes($todays_revenue_video + $yesterdays_revenue_video + $this_months_revenue_video[2]
            + $this_months_revenue_video[3] + $this_months_revenue_video[4] + $this_months_revenue_video[5] + $this_months_revenue_video[6]);
        $last_30_days_revenue_video = $this->trimTrailingZeroes(array_sum($this_months_revenue_video));
        $this_years_revenue_video = $this->trimTrailingZeroes($user->commissions_video);

        $past_views_video_key = "$prefix_video.past_views_$user_id";
        if (Cache::has($past_views_video_key)) {
            $past_views_video = Cache::get($past_views_video_key);
            $todays_views_video = $past_views_video[0];
            $yesterdays_views_video = $past_views_video[1];
            $last_7_days_views_video = $past_views_video[6] + $past_views_video[5] + $past_views_video[4]
                + $past_views_video[3] + $past_views_video[2] + $past_views_video[1] + $todays_views_video;
            $last_30_days_views_video = array_sum($past_views_video);
        } else {
            // array that holds the amount of views for each day of the past 30 days
            $past_views_video = [];
            for ($i = 0; $i < 30; $i++) {
                $past_views_video[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->count();
            }

            $todays_views_video = $past_views_video[0];
            $yesterdays_views_video = $past_views_video[1];
            $last_7_days_views_video = $past_views_video[6] + $past_views_video[5] + $past_views_video[4]
                + $past_views_video[3] + $past_views_video[2] + $past_views_video[1] + $todays_views_video;
            $last_30_days_views_video = array_sum($past_views_video);

            Cache::put($past_views_video_key, $past_views_video, $expires_at);
        }

        $videos = [[
            'todays_views'         => $todays_views_video,
            'yesterdays_views'     => $yesterdays_views_video,
            'last_7_days_views'    => $last_7_days_views_video,
            'last_30_days_views'   => $last_30_days_views_video,
            'this_years_views'     => $this_years_views_video,
            'todays_revenue'       => $todays_revenue_video,
            'yesterdays_revenue'   => $yesterdays_revenue_video,
            'last_7_days_revenue'  => $last_7_days_revenue_video,
            'last_30_days_revenue' => $last_30_days_revenue_video,
            'this_years_revenue'   => $this_years_revenue_video,
        ]];

        return $videos;
    }

    public function getImageStats($user_id, $user, $expires_at, $expires_at_interval)
    {
        $prefix_image = 'image';
        $this_months_revenue_image = [];
        $this_months_revenue_image_key = "$prefix_image.this_months_revenue_$user_id";
        if (Cache::has($this_months_revenue_image_key)) {
            $this_months_revenue_image = Cache::get($this_months_revenue_image_key);

            $last_update = new Carbon($this_months_revenue_image['last_update']);
            $now = Carbon::now();
            $diff_in_days = $last_update->diffInDays($now);
            $diff_in_minutes = $last_update->diffInMinutes($now);

            // updating of the arrays
            // if there is 1 or more day difference from when the array was last updated, then shift the array
            if ($diff_in_days >= 1 && $diff_in_days < 30) {
                // shift array however many days difference there was
                for ($x = 0; $x < $diff_in_days; $x++) {
                    for ($i = 0; $i < 30; $i++) {
                        $this_months_revenue_image[$i + 1] = $this_months_revenue_image[$i];
                    }
                }

                // echo "update because $diff_in_days since last update";
                // add however many days are different stats to the array again to get an updated statistics
                for ($i = 0; $i < $diff_in_days; $i++) {
                    $this_months_revenue_image[$i] = array_sum(AffiliateImageView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }
                // this key stores the last time the array was updated
                $this_months_revenue_image['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_image_key, $this_months_revenue_image);

                // update the whole array
            } elseif ($diff_in_days > 29) {
                for ($i = 0; $i < 30; $i++) {
                    $this_months_revenue_image[$i] = array_sum(AffiliateImageView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }

                $this_months_revenue_image['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_image_key, $this_months_revenue_image, $expires_at);

                // else update today's values if the last time updated is more than $expires_at_interval minutes
            } elseif ($diff_in_minutes >= $expires_at_interval) {
                // echo "update because $diff_in_minutes >= $expires_at_interval";
                // add today's stats to the array again to get an updated array
                $this_months_revenue_image[0] = array_sum(AffiliateImageView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(0)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays(0)->endOfDay())
                    ->pluck('commission')
                    ->toArray());

                // this key stores the last time the array was updated
                $this_months_revenue_image['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_image_key, $this_months_revenue_image);
            }
            // echo "no update because diff_in_days $diff_in_days and diffinminutes $diff_in_minutes";
        } else {
            // echo "no cache is present";
            // If no cache is present
            // Revenue today, the past 7 days, and 30 days stored in one array
            for ($i = 0; $i < 30; $i++) {
                $this_months_revenue_image[$i] = array_sum(AffiliateImageView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->pluck('commission')
                    ->toArray());
            }
            $this_months_revenue_image['last_update'] = Carbon::now();
            Cache::forever($this_months_revenue_image_key, $this_months_revenue_image, $expires_at);
        }

        $todays_revenue_image = $this->trimTrailingZeroes($this_months_revenue_image[0]);
        $yesterdays_revenue_image = $this->trimTrailingZeroes($this_months_revenue_image[1]);
        $last_7_days_revenue_image = $this->trimTrailingZeroes($todays_revenue_image + $yesterdays_revenue_image + $this_months_revenue_image[2]
            + $this_months_revenue_image[3] + $this_months_revenue_image[4] + $this_months_revenue_image[5] + $this_months_revenue_image[6]);
        $last_30_days_revenue_image = $this->trimTrailingZeroes(array_sum($this_months_revenue_image));
        $this_years_revenue_image = $this->trimTrailingZeroes($user->commissions_image);

        $past_views_image_key = "$prefix_image.past_views_$user_id";
        if (Cache::has($past_views_image_key)) {
            $past_views_image = Cache::get($past_views_image_key);
            $todays_views_image = $past_views_image[0];
            $yesterdays_views_image = $past_views_image[1];
            $last_7_days_views_image = $past_views_image[6] + $past_views_image[5] + $past_views_image[4]
                + $past_views_image[3] + $past_views_image[2] + $past_views_image[1] + $todays_views_image;
            $last_30_days_views_image = array_sum($past_views_image);
        } else {
            // array that holds the amount of views for each day of the past 30 days
            $past_views_image = [];
            for ($i = 0; $i < 30; $i++) {
                $past_views_image[$i] = AffiliateImageView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->count();
            }

            $todays_views_image = $past_views_image[0];
            $yesterdays_views_image = $past_views_image[1];
            $last_7_days_views_image = $past_views_image[6] + $past_views_image[5] + $past_views_image[4]
                + $past_views_image[3] + $past_views_image[2] + $past_views_image[1] + $todays_views_image;
            $last_30_days_views_image = array_sum($past_views_image);

            Cache::put($past_views_image_key, $past_views_image, $expires_at);
        }

        $this_years_views_image_key = "$prefix_image.this_years_views_$user_id";
        if (Cache::has($this_years_views_image_key)) {
            $this_years_views_image = Cache::get($this_years_views_image_key);
        } else {
            $this_years_views_image = AffiliateImageView::where('owner_id', $user->id)
                ->where('created_at', '>=', Carbon::parse('first day of January'))
                ->where('created_at', '<=', Carbon::parse('first day of January next year')->subSecond())
                ->count();
            Cache::put($this_years_views_image_key, $this_years_views_image, $expires_at);
        }

        $image_stats = [[
            'todays_views'         => $todays_views_image,
            'yesterdays_views'     => $yesterdays_views_image,
            'last_7_days_views'    => $last_7_days_views_image,
            'last_30_days_views'   => $last_30_days_views_image,
            'this_years_views'     => $this_years_views_image,
            'todays_revenue'       => $todays_revenue_image,
            'yesterdays_revenue'   => $yesterdays_revenue_image,
            'last_7_days_revenue'  => $last_7_days_revenue_image,
            'last_30_days_revenue' => $last_30_days_revenue_image,
            'this_years_revenue'   => $this_years_revenue_image,
        ]];

        return $image_stats;
    }

    public function getAudioStats($user_id, $user, $expires_at, $expires_at_interval)
    {
        $prefix_audio = 'audio';
        $this_months_revenue_audio = [];
        $this_months_revenue_audio_key = "$prefix_audio.this_months_revenue_$user_id";
        if (Cache::has($this_months_revenue_audio_key)) {
            $this_months_revenue_audio = Cache::get($this_months_revenue_audio_key);

            $last_update = new Carbon($this_months_revenue_audio['last_update']);
            $now = Carbon::now();
            $diff_in_days = $last_update->diffInDays($now);
            $diff_in_minutes = $last_update->diffInMinutes($now);

            // updating of the arrays
            // if there is 1 or more day difference from when the array was last updated, then shift the array
            if ($diff_in_days >= 1 && $diff_in_days < 30) {
                // shift array however many days difference there was
                for ($x = 0; $x < $diff_in_days; $x++) {
                    for ($i = 0; $i < 30; $i++) {
                        $this_months_revenue_audio[$i + 1] = $this_months_revenue_audio[$i];
                    }
                }

                // echo "update because $diff_in_days since last update";
                // add however many days are different stats to the array again to get an updated statistics
                for ($i = 0; $i < $diff_in_days; $i++) {
                    $this_months_revenue_audio[$i] = array_sum(AffiliateAudioView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }
                // this key stores the last time the array was updated
                $this_months_revenue_audio['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_audio_key, $this_months_revenue_audio);

                // update the whole array
            } elseif ($diff_in_days > 29) {
                for ($i = 0; $i < 30; $i++) {
                    $this_months_revenue_audio[$i] = array_sum(AffiliateAudioView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }

                $this_months_revenue_audio['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_audio_key, $this_months_revenue_audio, $expires_at);

                // else update today's values if the last time updated is more than $expires_at_interval minutes
            } elseif ($diff_in_minutes >= $expires_at_interval) {
                // echo "update because $diff_in_minutes >= $expires_at_interval";
                // add today's stats to the array again to get an updated array
                $this_months_revenue_audio[0] = array_sum(AffiliateAudioView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(0)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays(0)->endOfDay())
                    ->pluck('commission')
                    ->toArray());

                // this key stores the last time the array was updated
                $this_months_revenue_audio['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_audio_key, $this_months_revenue_audio);
            }
            // echo "no update because diff_in_days $diff_in_days and diffinminutes $diff_in_minutes";
        } else {
            // echo "no cache is present";
            // If no cache is present
            // Revenue today, the past 7 days, and 30 days stored in one array
            for ($i = 0; $i < 30; $i++) {
                $this_months_revenue_audio[$i] = array_sum(AffiliateAudioView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->pluck('commission')
                    ->toArray());
            }
            $this_months_revenue_audio['last_update'] = Carbon::now();
            Cache::forever($this_months_revenue_audio_key, $this_months_revenue_audio, $expires_at);
        }

        $todays_revenue_audio = $this->trimTrailingZeroes($this_months_revenue_audio[0]);
        $yesterdays_revenue_audio = $this->trimTrailingZeroes($this_months_revenue_audio[1]);
        $last_7_days_revenue_audio = $this->trimTrailingZeroes($todays_revenue_audio + $yesterdays_revenue_audio + $this_months_revenue_audio[2]
            + $this_months_revenue_audio[3] + $this_months_revenue_audio[4] + $this_months_revenue_audio[5] + $this_months_revenue_audio[6]);
        $last_30_days_revenue_audio = $this->trimTrailingZeroes(array_sum($this_months_revenue_audio));
        $this_years_revenue_audio = $this->trimTrailingZeroes($user->commissions_audio);

        $past_views_audio_key = "$prefix_audio.past_views_$user_id";
        if (Cache::has($past_views_audio_key)) {
            $past_views_audio = Cache::get($past_views_audio_key);
            $todays_views_audio = $past_views_audio[0];
            $yesterdays_views_audio = $past_views_audio[1];
            $last_7_days_views_audio = $past_views_audio[6] + $past_views_audio[5] + $past_views_audio[4]
                + $past_views_audio[3] + $past_views_audio[2] + $past_views_audio[1] + $todays_views_audio;
            $last_30_days_views_audio = array_sum($past_views_audio);
        } else {
            // array that holds the amount of views for each day of the past 30 days
            $past_views_audio = [];
            for ($i = 0; $i < 30; $i++) {
                $past_views_audio[$i] = AffiliateAudioView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->count();
            }

            $todays_views_audio = $past_views_audio[0];
            $yesterdays_views_audio = $past_views_audio[1];
            $last_7_days_views_audio = $past_views_audio[6] + $past_views_audio[5] + $past_views_audio[4]
                + $past_views_audio[3] + $past_views_audio[2] + $past_views_audio[1] + $todays_views_audio;
            $last_30_days_views_audio = array_sum($past_views_audio);

            Cache::put($past_views_audio_key, $past_views_audio, $expires_at);
        }

        $this_years_views_audio_key = "$prefix_audio.this_years_views_$user_id";
        if (Cache::has($this_years_views_audio_key)) {
            $this_years_views_audio = Cache::get($this_years_views_audio_key);
        } else {
            $this_years_views_audio = AffiliateAudioView::where('owner_id', $user->id)
                ->where('created_at', '>=', Carbon::parse('first day of January'))
                ->where('created_at', '<=', Carbon::parse('first day of January next year')->subSecond())
                ->count();
            Cache::put($this_years_views_audio_key, $this_years_views_audio, $expires_at);
        }

        $audio_stats = [[
            'todays_views'         => $todays_views_audio,
            'yesterdays_views'     => $yesterdays_views_audio,
            'last_7_days_views'    => $last_7_days_views_audio,
            'last_30_days_views'   => $last_30_days_views_audio,
            'this_years_views'     => $this_years_views_audio,
            'todays_revenue'       => $todays_revenue_audio,
            'yesterdays_revenue'   => $yesterdays_revenue_audio,
            'last_7_days_revenue'  => $last_7_days_revenue_audio,
            'last_30_days_revenue' => $last_30_days_revenue_audio,
            'this_years_revenue'   => $this_years_revenue_audio,
        ]];

        return $audio_stats;
    }
}
