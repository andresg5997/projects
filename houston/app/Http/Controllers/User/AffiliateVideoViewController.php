<?php

namespace App\Http\Controllers\User;

use App;
use App\AffiliateVideoView;
use App\Attachment;
use App\Media;
use App\User;
use Auth;
use Carbon\Carbon;
use GeoIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AffiliateVideoViewController extends AffiliateController
{
    public function create(Request $request, $slug, $embed)
    {
        $media = Media::where('slug', $slug)->firstOrFail();
        $media_id = $media->id;
        $owner_id = $media->user_id;
        $user = User::where('id', $owner_id)->first();
        $all_time_account_balance = $user->all_time_account_balance;
        $current_account_balance = $user->current_account_balance;
        $commissions_video = $user->commissions_video;
        $adblock = $request->input('test');
        $adblock_multiplier = 1;
        if ($adblock == 1) {
            $adblock_multiplier = 0.1;
        }

        $percent_played = $request->input('percent_played');

        $attachment = Attachment::where('media_id', $media_id)->first();
        $duration = json_decode($attachment->content)->duration;
        sscanf($duration, '%d:%d:%d.%d', $hours, $minutes, $seconds, $milliseconds);
        if ($minutes > 10) {
            $ads_multiplier = 1;
        } elseif ($minutes > 9) {
            $ads_multiplier = 0.9;
        } elseif ($minutes > 8) {
            $ads_multiplier = 0.8;
        } elseif ($minutes > 7) {
            $ads_multiplier = 0.7;
        } elseif ($minutes > 6) {
            $ads_multiplier = 0.6;
        } elseif ($minutes > 5) {
            $ads_multiplier = 0.5;
        } elseif ($minutes > 4) {
            $ads_multiplier = 0.4;
        } elseif ($minutes > 3) {
            $ads_multiplier = 0.3;
        } elseif ($minutes > 2) {
            $ads_multiplier = 0.2;
        } else {
            $ads_multiplier = 0.1;
        }

        $ip = $request->ip();
        $geoIP = GeoIP::getLocation($ip);

        $state = $geoIP->state;
        $city = $geoIP->city;
        $country = $geoIP->country;

        $country_group_1_list = config('country_group_1_list');
        $country_group_2_list = config('country_group_2_list');
        $country_group_3_list = config('country_group_3_list');

        $pos = strpos($country_group_1_list, $country);
        $pos2 = strpos($country_group_2_list, $country);
        $pos3 = strpos($country_group_3_list, $country);

        if ($pos !== false) {
            $amount_per_10000 = config('amount_for_country_group_1');
            $country_group = 1;
        } elseif ($pos2 !== false) {
            $amount_per_10000 = config('amount_for_country_group_2');
            $country_group = 2;
        } elseif ($pos3 !== false) {
            $amount_per_10000 = config('amount_for_country_group_3');
            $country_group = 3;
        } else {
            $amount_per_10000 = config('amount_for_country_group_4');
            $country_group = 4;
        }

        $amount_per_1_view = $amount_per_10000 / 10000;
        // multiply by 0.1 because this gets called every time an additional 10% is watched
        // so $commission is the value earned for each 10
        $commission = $amount_per_1_view * $ads_multiplier * 0.1 * $adblock_multiplier;
        $new_commissions_video = $commissions_video + $commission;
        $new_current_account_balance = $current_account_balance + $commission;
        $new_all_time_account_balance = $all_time_account_balance + $commission;

        // check if owner of the media has a referrer
        // if owner was referred by anyone, credit him as well
        $referred_by = $user->referred_by;
        if ($referred_by) {
            $referrer = User::where('affiliate_id', $referred_by)->first();
            $new_current_referral_balance = $referrer->current_referral_balance + ($commission * config('referral_multiplier'));
            $new_all_time_referral_balance = $referrer->all_time_referral_balance + ($commission * config('referral_multiplier'));
            $referrer->update([
                'current_referral_balance'  => $new_current_referral_balance,
                'all_time_referral_balance' => $new_all_time_referral_balance,
            ]);
        }

        if (Auth::check()) {
            $user_id = $request->user()->id;
        } else {
            $user_id = 0;
        }

        // create or update the affiliate view/download if it already exists
        // update the account balance when creating or updating the affiliate view/download
        $affiliate = new AffiliateVideoView();
        if ($affiliate->where('ip', $ip)
                ->where('media_id', $media_id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count() == 0) {
            $affiliate->create([
                'media_id'          => $media_id,
                'user_id'           => $user_id,
                'owner_id'          => $owner_id,
                'ads_multiplier'    => $ads_multiplier,
                'country'           => $country,
                'country_group'     => $country_group,
                'state'             => $state,
                'city'              => $city,
                'ip'                => $ip,
                'adblock'           => $adblock,
                'percent_played'    => $percent_played,
                'commission'        => $commission,
                'embed'             => $embed,
            ]);

            $user->where('id', $owner_id)->update([
                'current_account_balance'   => $new_current_account_balance,
                'all_time_account_balance'  => $new_all_time_account_balance,
                'commissions_video'         => $new_commissions_video,
            ]);

            return response()->json('Affiliate view counted.', 200);
        } elseif ($affiliate->where('ip', $ip)
                ->where('media_id', $media_id)
                ->where('percent_played', '<', $percent_played)->count() == 1) {
            $affiliate->where('ip', $ip)
                ->where('media_id', $media_id)
                ->where('percent_played', '<', $percent_played)
                ->update([
                    'percent_played'  => $percent_played,
                    'commission'      => ($commission * ($percent_played / 10)),
                ]);

            $user->where('id', $owner_id)->update([
                'current_account_balance'    => $new_current_account_balance,
                'all_time_account_balance'   => $new_all_time_account_balance,
                'commissions_video'          => $new_commissions_video,
            ]);

            return response()->json('Affiliate view counted.', 200);
        } else {
            return response()->json('Affiliate view not counted, because you have already watched this far today. If you have not finished the video, finish it and we will count the remaining percentages. If you did finish, try tomorrow again.', 200);
        }
    }

    public function statistics(Request $request)
    {
        $page = 'statistics.video';
        $user = $request->user();
        $user_id = $user->id;
        $expires_at_interval = config('expires_at_interval');
        $expires_at = Carbon::now()->addMinutes($expires_at_interval);
        $prefix = 'video';

        $yesterdays_viewsFromTo_key = "$prefix.yesterdays_viewsFromTo_$user_id";
        $todays_viewsFromTo_key = "$prefix.todays_viewsFromTo_$user_id";

        if (Cache::has($yesterdays_viewsFromTo_key)) {
            $yesterdays_viewsFromTo = Cache::get($yesterdays_viewsFromTo_key);
            $todays_viewsFromTo = Cache::get($todays_viewsFromTo_key);
        } else {
            $yesterdays_viewsFromTo = [];
            $todays_viewsFromTo = [];

            for ($i = 0; $i < 12; $i++) {
                if ($i == 0) {
                    $yesterdays_viewsFromTo[$i] = AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::yesterday()->startOfDay())
                        ->where('created_at', '<=', Carbon::yesterday()->addHour(2))
                        ->count();

                    $todays_viewsFromTo[$i] = AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->addHour(2))
                        ->count();
                } else {
                    $from = $i * 2;
                    $to = $from + 2;
                    $yesterdays_viewsFromTo[$i] = AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::yesterday()->addHour($from))
                        ->where('created_at', '<=', Carbon::yesterday()->addHour($to))
                        ->count();

                    $todays_viewsFromTo[$i] = AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->addHour($from))
                        ->where('created_at', '<=', Carbon::today()->addHour($to))
                        ->count();
                }
            }
            Cache::put($yesterdays_viewsFromTo_key, $yesterdays_viewsFromTo, $expires_at);
            Cache::put($todays_viewsFromTo_key, $todays_viewsFromTo, $expires_at);
        }

        $line_chart = app()->chartjs
            ->name('yesterdayVsToday')
            ->type('line')
            ->labels(
                [
                    '2:00', '4:00', '6:00', '8:00', '10:00', '12:00',
                    '14:00', '16:00', '18:00', '20:00', '22:00', '24:00',
                ]
            )
            ->datasets([
                [
                    'label'                     => "Yesterday's Views",
                    'backgroundColor'           => 'rgba(255, 99, 132, 0.31)',
                    'borderColor'               => 'rgba(255, 99, 132, 0.7)',
                    'pointBorderColor'          => 'rgba(255, 99, 132, 0.7)',
                    'pointBackgroundColor'      => 'rgba(255, 99, 132, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$yesterdays_viewsFromTo[0], $yesterdays_viewsFromTo[1], $yesterdays_viewsFromTo[2],
                        $yesterdays_viewsFromTo[3], $yesterdays_viewsFromTo[4], $yesterdays_viewsFromTo[5],
                        $yesterdays_viewsFromTo[6], $yesterdays_viewsFromTo[7], $yesterdays_viewsFromTo[8],
                        $yesterdays_viewsFromTo[9], $yesterdays_viewsFromTo[10], $yesterdays_viewsFromTo[11], ],
                    'fill' => false,
                ],
                [
                    'label'                     => "Today's Views",
                    'backgroundColor'           => 'rgba(54, 162, 235, 0.31)',
                    'borderColor'               => 'rgba(54, 162, 235, 0.7)',
                    'pointBorderColor'          => 'rgba(54, 162, 235, 0.7)',
                    'pointBackgroundColor'      => 'rgba(54, 162, 235, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$todays_viewsFromTo[0], $todays_viewsFromTo[1], $todays_viewsFromTo[2],
                        $todays_viewsFromTo[3], $todays_viewsFromTo[4], $todays_viewsFromTo[5],
                        $todays_viewsFromTo[6], $todays_viewsFromTo[7], $todays_viewsFromTo[8],
                        $todays_viewsFromTo[9], $todays_viewsFromTo[10], $todays_viewsFromTo[11], ],
                    'fill' => false,
                ],
            ])
            ->options([
                'responsive' => true,
                'legend'     => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => false,
                    'text'    => "Today's vs Yesterday's Views",
                ],
                'tooltips' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
                'hover' => [
                    'mode'      => 'nearest',
                    'intersect' => true,
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'display'    => true,
                            'scaleLabel' => [
                                'display'     => true,
                                'labelString' => 'Hour of Day',
                            ],
                        ],
                    ],
                    'yAxes' => [
                        [
                            'display'    => true,
                            'scaleLabel' => [
                                'display'     => true,
                                'labelString' => 'Total Views',
                            ],
                            'ticks' => [
                                'beginAtZero'   => true,
                                'maxTicksLimit' => '6',
                            ],
                        ],
                    ],
                ],
            ]);

        $past_views_key = "$prefix.past_views_$user_id";

        if (Cache::has($past_views_key)) {
            $past_views = Cache::get($past_views_key);
            $todays_views = $past_views[0];
            $yesterdays_views = $past_views[1];
        } else {
            // array that holds the amount of views for each day of the past 30 days
            $past_views = [];
            for ($i = 0; $i < 30; $i++) {
                $past_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->count();
            }

            $todays_views = $past_views[0];
            $yesterdays_views = $past_views[1];

            Cache::put($past_views_key, $past_views, $expires_at);
        }

        $nameOfDayXDaysAgo = [];
        for ($i = 0; $i <= 6; $i++) {
            $nameOfDayXDaysAgo[$i] = Carbon::today()->subDays($i)->format('l');
        }

        $bar_chart = app()->chartjs
            ->name('barChart')
            ->type('bar')
            ->labels(['Today', $nameOfDayXDaysAgo[1], $nameOfDayXDaysAgo[2], $nameOfDayXDaysAgo[3],
                $nameOfDayXDaysAgo[4], $nameOfDayXDaysAgo[5], $nameOfDayXDaysAgo[6], ])
            ->datasets([
                [
                    'label'           => 'Views',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                    'borderWidth'     => 1,
                    'data'            => [$todays_views, $yesterdays_views, $past_views[2], $past_views[3],
                        $past_views[4], $past_views[5], $past_views[6], ],
                ],
            ])
            ->options([
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero'   => true,
                                'maxTicksLimit' => '6',
                            ],
                        ],
                    ],
                ],
            ]);

        /*
         *  Get all the statistics for the views of the last 30 days
         *  after they are cached only update the today's values
         *  or update however many days were missed.
         *  In case the last update was 30+ days ago, then get all the stats again
         *  and re-cache it forever
         */

        $views_last_30_days_key = "$prefix.views_last_30_days_$user_id";
        if (Cache::has($views_last_30_days_key)) {
            $views_last_30_days = Cache::get($views_last_30_days_key);

            $last_update = new Carbon($views_last_30_days['last_update']);
            $now = Carbon::now();
            $diff_in_days = $last_update->diffInDays($now);
            $diff_in_minutes = $last_update->diffInMinutes($now);

            // updating of the arrays
            // if there is 1 or more day difference from when the array was last updated, then shift the array
            if ($diff_in_days >= 1 && $diff_in_days < 30) {
                // shift array however many days difference there was
                for ($x = 0; $x < $diff_in_days; $x++) {
                    for ($i = 0; $i < 30; $i++) {
                        $views_last_30_days[$i + 1] = $views_last_30_days[$i];
                    }
                }

                // add today's stats to the array again to get an updated statistics
                for ($day = 0; $day < $diff_in_days; $day++) {
                    for ($i = 1; $i <= 4; $i++) {
                        // loop through all the ads_multiplier
                        for ($x = 0.1; $x <= 1; $x += 0.1) {
                            // loop through all the percentage watched
                            $multiplier = round($x * 100);
                            for ($z = 10; $z <= 100; $z += 10) {
                                $views_last_30_days[$day][$i][(int) $multiplier][$z] = AffiliateVideoView::where('owner_id', $user->id)
                                    ->where('percent_played', $z)
                                    ->where('country_group', $i)
                                    ->where('created_at', '>=', Carbon::today()->subDays($day)->startOfDay())
                                    ->where('created_at', '<=', Carbon::today()->subDays($day)->endOfDay())
                                    ->where('ads_multiplier', $x)
                                    ->count();
                            }
                        }
                    }
                }
                // this key stores the last time the array was updated
                $views_last_30_days['last_update'] = Carbon::now();
                Cache::forever($views_last_30_days_key, $views_last_30_days);

                // update the whole array
            } elseif ($diff_in_days > 29) {
                $views_last_30_days = [];
                // loop through all the 30 days
                for ($day = 0; $day < 30; $day++) {
                    // loop through all the country groups
                    for ($i = 1; $i <= 4; $i++) {
                        // loop through all the ads_multiplier
                        for ($x = 0.1; $x <= 1; $x += 0.1) {
                            // loop through all the percentage watched
                            $multiplier = round($x * 100);
                            for ($z = 10; $z <= 100; $z += 10) {
                                $views_last_30_days[$day][$i][(int) $multiplier][$z] = AffiliateVideoView::where('owner_id', $user->id)
                                    ->where('percent_played', $z)
                                    ->where('country_group', $i)
                                    ->where('created_at', '>=', Carbon::today()->subDays($day)->startOfDay())
                                    ->where('created_at', '<=', Carbon::today()->subDays($day)->endOfDay())
                                    ->where('ads_multiplier', $x)
                                    ->count();
                            }
                        }
                    }
                }
                // this key stores the last time the array was cached
                $views_last_30_days['last_update'] = Carbon::now();
                Cache::forever($views_last_30_days_key, $views_last_30_days);

                // else update today's values
            } elseif ($diff_in_minutes >= $expires_at_interval) {
                // echo "update because $diff_in_minutes >= $expires_at_interval";
                // add today's stats to the array again to get an updated array
                for ($i = 1; $i <= 4; $i++) {
                    // loop through all the ads_multiplier
                    for ($x = 0.1; $x <= 1; $x += 0.1) {
                        // loop through all the percentage watched
                        $multiplier = round($x * 100);
                        for ($z = 10; $z <= 100; $z += 10) {
                            $views_last_30_days[0][$i][(int) $multiplier][$z] = AffiliateVideoView::where('owner_id', $user->id)
                                ->where('percent_played', $z)
                                ->where('country_group', $i)
                                ->where('created_at', '>=', Carbon::today()->subDays(0)->startOfDay())
                                ->where('created_at', '<=', Carbon::today()->subDays(0)->endOfDay())
                                ->where('ads_multiplier', $x)
                                ->count();
                        }
                    }
                }
                // this key stores the last time the array was updated
                $views_last_30_days['last_update'] = Carbon::now();
                Cache::forever($views_last_30_days_key, $views_last_30_days);
            }
            // if no cache is present
        } else {
            $views_last_30_days = [];
            // loop through all the 30 days
            for ($day = 0; $day < 30; $day++) {
                // loop through all the country groups
                for ($i = 1; $i <= 4; $i++) {
                    // loop through all the ads_multiplier
                    for ($x = 0.1; $x <= 1; $x += 0.1) {
                        // loop through all the percentage watched
                        $multiplier = round($x * 100);
                        for ($z = 10; $z <= 100; $z += 10) {
                            $views_last_30_days[$day][$i][(int) $multiplier][$z] = AffiliateVideoView::where('owner_id', $user->id)
                                ->where('percent_played', $z)
                                ->where('country_group', $i)
                                ->where('created_at', '>=', Carbon::today()->subDays($day)->startOfDay())
                                ->where('created_at', '<=', Carbon::today()->subDays($day)->endOfDay())
                                ->where('ads_multiplier', $x)
                                ->count();
                        }
                    }
                }
            }
            // this key stores the last time the array was cached
            $views_last_30_days['last_update'] = Carbon::now();
            // Cache it forever because this file never gets deleted
            Cache::forever($views_last_30_days_key, $views_last_30_days);
        }

        $last_7_days_views = $past_views[6] + $past_views[5] + $past_views[4]
            + $past_views[3] + $past_views[2] + $past_views[1] + $todays_views;

        $last_30_days_views = array_sum($past_views);

        $this_years_views_key = "$prefix.this_years_views_$user_id";
        if (Cache::has($this_years_views_key)) {
            $this_years_views = Cache::get($this_years_views_key);
        } else {
            $this_years_views = AffiliateVideoView::where('owner_id', $user->id)
                ->where('created_at', '>=', Carbon::parse('first day of January'))
                ->where('created_at', '<=', Carbon::parse('first day of January next year')->subSecond())
                ->count();
            Cache::put($this_years_views_key, $this_years_views, $expires_at);
        }

        $percentages_watched_key = "$prefix.percentages_watched_$user_id";
        if (Cache::has($percentages_watched_key)) {
            $percentages_watched = Cache::get($percentages_watched_key);
        } else {
            $percentages_watched = [];

            for ($x = 10; $x <= 100; $x++) {
                for ($i = 0; $i < 30; $i++) {
                    $percentages_watched[$x][$i] = AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->where('percent_played', $x)
                        ->count();
                }
            }

            Cache::put($percentages_watched_key, $percentages_watched, $expires_at);
        }

        $dayLabels = [];
        for ($i = 0; $i < 30; $i++) {
            $dayLabels[$i] = Carbon::today()->subDays($i)->month.'/'.Carbon::today()->subDays($i)->day;
        }

        $month_chart = app()->chartjs
            ->name('month_chart')
            ->type('line')
            ->labels(
                [
                    $dayLabels[0], $dayLabels[1], $dayLabels[2], $dayLabels[3], $dayLabels[4], $dayLabels[5],
                    $dayLabels[6], $dayLabels[7], $dayLabels[8], $dayLabels[9], $dayLabels[10], $dayLabels[11],
                    $dayLabels[12], $dayLabels[13], $dayLabels[14], $dayLabels[15], $dayLabels[16], $dayLabels[17],
                    $dayLabels[18], $dayLabels[19], $dayLabels[20], $dayLabels[21], $dayLabels[22], $dayLabels[23],
                    $dayLabels[24], $dayLabels[25], $dayLabels[26], $dayLabels[27], $dayLabels[28], $dayLabels[29],
                ]
            )
            ->datasets([
                [
                    'label'                     => '100%',
                    'backgroundColor'           => 'rgba(63, 63, 191, 0.31)',
                    'borderColor'               => 'rgba(63, 63, 191, 0.7)',
                    'pointBorderColor'          => 'rgba(63, 63, 191, 0.7)',
                    'pointBackgroundColor'      => 'rgba(63, 63, 191, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[100][0], $percentages_watched[100][1], $percentages_watched[100][2],
                        $percentages_watched[100][3], $percentages_watched[100][4], $percentages_watched[100][5],
                        $percentages_watched[100][6], $percentages_watched[100][7], $percentages_watched[100][8],
                        $percentages_watched[100][9], $percentages_watched[100][10], $percentages_watched[100][11],
                        $percentages_watched[100][12], $percentages_watched[100][13], $percentages_watched[100][14],
                        $percentages_watched[100][15], $percentages_watched[100][16], $percentages_watched[100][17],
                        $percentages_watched[100][18], $percentages_watched[100][19], $percentages_watched[100][20],
                        $percentages_watched[100][21], $percentages_watched[100][22], $percentages_watched[100][23],
                        $percentages_watched[100][24], $percentages_watched[100][25], $percentages_watched[100][26],
                        $percentages_watched[100][27], $percentages_watched[100][28], $percentages_watched[100][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '90%',
                    'backgroundColor'           => 'rgba(63, 127, 191, 0.31)',
                    'borderColor'               => 'rgba(63, 127, 191, 0.7)',
                    'pointBorderColor'          => 'rgba(63, 127, 191, 0.7)',
                    'pointBackgroundColor'      => 'rgba(63, 127, 191, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[90][0], $percentages_watched[90][1], $percentages_watched[90][2],
                        $percentages_watched[90][3], $percentages_watched[90][4], $percentages_watched[90][5],
                        $percentages_watched[90][6], $percentages_watched[90][7], $percentages_watched[90][8],
                        $percentages_watched[90][9], $percentages_watched[90][10], $percentages_watched[90][11],
                        $percentages_watched[90][12], $percentages_watched[90][13], $percentages_watched[90][14],
                        $percentages_watched[90][15], $percentages_watched[90][16], $percentages_watched[90][17],
                        $percentages_watched[90][18], $percentages_watched[90][19], $percentages_watched[90][20],
                        $percentages_watched[90][21], $percentages_watched[90][22], $percentages_watched[90][23],
                        $percentages_watched[90][24], $percentages_watched[90][25], $percentages_watched[90][26],
                        $percentages_watched[90][27], $percentages_watched[90][28], $percentages_watched[90][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '80%',
                    'backgroundColor'           => 'rgba(63, 191, 191, 0.31)',
                    'borderColor'               => 'rgba(63, 191, 191, 0.7)',
                    'pointBorderColor'          => 'rgba(63, 191, 191, 0.7)',
                    'pointBackgroundColor'      => 'rgba(63, 191, 191, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[80][0], $percentages_watched[80][1], $percentages_watched[80][2],
                        $percentages_watched[80][3], $percentages_watched[80][4], $percentages_watched[80][5],
                        $percentages_watched[80][6], $percentages_watched[80][7], $percentages_watched[80][8],
                        $percentages_watched[80][9], $percentages_watched[80][10], $percentages_watched[80][11],
                        $percentages_watched[80][12], $percentages_watched[80][13], $percentages_watched[80][14],
                        $percentages_watched[80][15], $percentages_watched[80][16], $percentages_watched[80][17],
                        $percentages_watched[80][18], $percentages_watched[80][19], $percentages_watched[80][20],
                        $percentages_watched[80][21], $percentages_watched[80][22], $percentages_watched[80][23],
                        $percentages_watched[80][24], $percentages_watched[80][25], $percentages_watched[80][26],
                        $percentages_watched[80][27], $percentages_watched[80][28], $percentages_watched[80][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '70%',
                    'backgroundColor'           => 'rgba(63, 191, 127, 0.31)',
                    'borderColor'               => 'rgba(63, 191, 127, 0.7)',
                    'pointBorderColor'          => 'rgba(63, 191, 127, 0.7)',
                    'pointBackgroundColor'      => 'rgba(63, 191, 127, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[70][0], $percentages_watched[70][1], $percentages_watched[70][2],
                        $percentages_watched[70][3], $percentages_watched[70][4], $percentages_watched[70][5],
                        $percentages_watched[70][6], $percentages_watched[70][7], $percentages_watched[70][8],
                        $percentages_watched[70][9], $percentages_watched[70][10], $percentages_watched[70][11],
                        $percentages_watched[70][12], $percentages_watched[70][13], $percentages_watched[70][14],
                        $percentages_watched[70][15], $percentages_watched[70][16], $percentages_watched[70][17],
                        $percentages_watched[70][18], $percentages_watched[70][19], $percentages_watched[70][20],
                        $percentages_watched[70][21], $percentages_watched[70][22], $percentages_watched[70][23],
                        $percentages_watched[70][24], $percentages_watched[70][25], $percentages_watched[70][26],
                        $percentages_watched[70][27], $percentages_watched[70][28], $percentages_watched[70][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '60%',
                    'backgroundColor'           => 'rgba(63, 191, 63, 0.31)',
                    'borderColor'               => 'rgba(63, 191, 63, 0.7)',
                    'pointBorderColor'          => 'rgba(63, 191, 63, 0.7)',
                    'pointBackgroundColor'      => 'rgba(63, 191, 63, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[60][0], $percentages_watched[60][1], $percentages_watched[60][2],
                        $percentages_watched[60][3], $percentages_watched[60][4], $percentages_watched[60][5],
                        $percentages_watched[60][6], $percentages_watched[60][7], $percentages_watched[60][8],
                        $percentages_watched[60][9], $percentages_watched[60][10], $percentages_watched[60][11],
                        $percentages_watched[60][12], $percentages_watched[60][13], $percentages_watched[60][14],
                        $percentages_watched[60][15], $percentages_watched[60][16], $percentages_watched[60][17],
                        $percentages_watched[60][18], $percentages_watched[60][19], $percentages_watched[60][20],
                        $percentages_watched[60][21], $percentages_watched[60][22], $percentages_watched[60][23],
                        $percentages_watched[60][24], $percentages_watched[60][25], $percentages_watched[60][26],
                        $percentages_watched[60][27], $percentages_watched[60][28], $percentages_watched[60][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '50%',
                    'backgroundColor'           => 'rgba(127, 191, 63, 0.31)',
                    'borderColor'               => 'rgba(127, 191, 63, 0.7)',
                    'pointBorderColor'          => 'rgba(127, 191, 63, 0.7)',
                    'pointBackgroundColor'      => 'rgba(127, 191, 63, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[50][0], $percentages_watched[50][1], $percentages_watched[50][2],
                        $percentages_watched[50][3], $percentages_watched[50][4], $percentages_watched[50][5],
                        $percentages_watched[50][6], $percentages_watched[50][7], $percentages_watched[50][8],
                        $percentages_watched[50][9], $percentages_watched[50][10], $percentages_watched[50][11],
                        $percentages_watched[50][12], $percentages_watched[50][13], $percentages_watched[50][14],
                        $percentages_watched[50][15], $percentages_watched[50][16], $percentages_watched[50][17],
                        $percentages_watched[50][18], $percentages_watched[50][19], $percentages_watched[50][20],
                        $percentages_watched[50][21], $percentages_watched[50][22], $percentages_watched[50][23],
                        $percentages_watched[50][24], $percentages_watched[50][25], $percentages_watched[50][26],
                        $percentages_watched[50][27], $percentages_watched[50][28], $percentages_watched[50][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '40%',
                    'backgroundColor'           => 'rgba(191, 191, 63, 0.31)',
                    'borderColor'               => 'rgba(191, 191, 63, 0.7)',
                    'pointBorderColor'          => 'rgba(191, 191, 63, 0.7)',
                    'pointBackgroundColor'      => 'rgba(191, 191, 63, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[40][0], $percentages_watched[40][1], $percentages_watched[40][2],
                        $percentages_watched[40][3], $percentages_watched[40][4], $percentages_watched[40][5],
                        $percentages_watched[40][6], $percentages_watched[40][7], $percentages_watched[40][8],
                        $percentages_watched[40][9], $percentages_watched[40][10], $percentages_watched[40][11],
                        $percentages_watched[40][12], $percentages_watched[40][13], $percentages_watched[40][14],
                        $percentages_watched[40][15], $percentages_watched[40][16], $percentages_watched[40][17],
                        $percentages_watched[40][18], $percentages_watched[40][19], $percentages_watched[40][20],
                        $percentages_watched[40][21], $percentages_watched[40][22], $percentages_watched[40][23],
                        $percentages_watched[40][24], $percentages_watched[40][25], $percentages_watched[40][26],
                        $percentages_watched[40][27], $percentages_watched[40][28], $percentages_watched[40][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '30%',
                    'backgroundColor'           => 'rgba(191, 127, 63, 0.31)',
                    'borderColor'               => 'rgba(191, 127, 63, 0.7)',
                    'pointBorderColor'          => 'rgba(191, 127, 63, 0.7)',
                    'pointBackgroundColor'      => 'rgba(191, 127, 63, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[30][0], $percentages_watched[30][1], $percentages_watched[30][2],
                        $percentages_watched[30][3], $percentages_watched[30][4], $percentages_watched[30][5],
                        $percentages_watched[30][6], $percentages_watched[30][7], $percentages_watched[30][8],
                        $percentages_watched[30][9], $percentages_watched[30][10], $percentages_watched[30][11],
                        $percentages_watched[30][12], $percentages_watched[30][13], $percentages_watched[30][14],
                        $percentages_watched[30][15], $percentages_watched[30][16], $percentages_watched[30][17],
                        $percentages_watched[30][18], $percentages_watched[30][19], $percentages_watched[30][20],
                        $percentages_watched[30][21], $percentages_watched[30][22], $percentages_watched[30][23],
                        $percentages_watched[30][24], $percentages_watched[30][25], $percentages_watched[30][26],
                        $percentages_watched[30][27], $percentages_watched[30][28], $percentages_watched[30][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '20%',
                    'backgroundColor'           => 'rgba(191, 63, 63, 0.31)',
                    'borderColor'               => 'rgba(191, 63, 63, 0.7)',
                    'pointBorderColor'          => 'rgba(191, 63, 63, 0.7)',
                    'pointBackgroundColor'      => 'rgba(191, 63, 63, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[20][0], $percentages_watched[20][1], $percentages_watched[20][2],
                        $percentages_watched[20][3], $percentages_watched[20][4], $percentages_watched[20][5],
                        $percentages_watched[20][6], $percentages_watched[20][7], $percentages_watched[20][8],
                        $percentages_watched[20][9], $percentages_watched[20][10], $percentages_watched[20][11],
                        $percentages_watched[20][12], $percentages_watched[20][13], $percentages_watched[20][14],
                        $percentages_watched[20][15], $percentages_watched[20][16], $percentages_watched[20][17],
                        $percentages_watched[20][18], $percentages_watched[20][19], $percentages_watched[20][20],
                        $percentages_watched[20][21], $percentages_watched[20][22], $percentages_watched[20][23],
                        $percentages_watched[20][24], $percentages_watched[20][25], $percentages_watched[20][26],
                        $percentages_watched[20][27], $percentages_watched[20][28], $percentages_watched[20][29], ],
                    'fill' => false,
                ],
                [
                    'label'                     => '10%',
                    'backgroundColor'           => 'rgba(191, 63, 127, 0.31)',
                    'borderColor'               => 'rgba(191, 63, 127, 0.7)',
                    'pointBorderColor'          => 'rgba(191, 63, 127, 0.7)',
                    'pointBackgroundColor'      => 'rgba(191, 63, 127, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => 'rgba(220,220,220,1)',
                    'data'                      => [$percentages_watched[10][0], $percentages_watched[10][1], $percentages_watched[10][2],
                        $percentages_watched[10][3], $percentages_watched[10][4], $percentages_watched[10][5],
                        $percentages_watched[10][6], $percentages_watched[10][7], $percentages_watched[10][8],
                        $percentages_watched[10][9], $percentages_watched[10][10], $percentages_watched[10][11],
                        $percentages_watched[10][12], $percentages_watched[10][13], $percentages_watched[10][14],
                        $percentages_watched[10][15], $percentages_watched[10][16], $percentages_watched[10][17],
                        $percentages_watched[10][18], $percentages_watched[10][19], $percentages_watched[10][20],
                        $percentages_watched[10][21], $percentages_watched[10][22], $percentages_watched[10][23],
                        $percentages_watched[10][24], $percentages_watched[10][25], $percentages_watched[10][26],
                        $percentages_watched[10][27], $percentages_watched[10][28], $percentages_watched[10][29], ],
                    'fill' => false,
                ],
            ])
            ->options([
                'responsive' => true,
                'legend'     => [
                    'position' => 'top',
                ],
                'title' => [
                    'display' => false,
                    'text'    => 'How much did the viewer watch?',
                ],
                'tooltips' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
                'hover' => [
                    'mode'      => 'nearest',
                    'intersect' => true,
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'display'    => true,
                            'scaleLabel' => [
                                'display'     => true,
                                'labelString' => 'Date',
                            ],
                        ],
                    ],
                    'yAxes' => [
                        [
                            'display'    => true,
                            'scaleLabel' => [
                                'display'     => true,
                                'labelString' => 'Total Views',
                            ],
                            'ticks' => [
                                'beginAtZero'   => true,
                                'maxTicksLimit' => '6',
                            ],
                        ],
                    ],
                ],
            ]);

        $todays_percentage_views_key = "$prefix.todays_percentage_views_$user_id";
        if (Cache::has($todays_percentage_views_key)) {
            $todays_percentage_views = Cache::get($todays_percentage_views_key);
        } else {
            $todays_percentage_views = [];
            for ($i = 10; $i <= 100; $i += 10) {
                $todays_percentage_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->endOfDay())
                    ->where('percent_played', $i)
                    ->count();
            }
            Cache::put($todays_percentage_views_key, $todays_percentage_views, $expires_at);
        }

        $yesterdays_percentage_views_key = "$prefix.yesterdays_percentage_views_$user_id";
        if (Cache::has($yesterdays_percentage_views_key)) {
            $yesterdays_percentage_views = Cache::get($yesterdays_percentage_views_key);
        } else {
            $yesterdays_percentage_views = [];
            for ($i = 10; $i <= 100; $i += 10) {
                $yesterdays_percentage_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::yesterday()->startOfDay())
                    ->where('created_at', '<=', Carbon::yesterday()->endOfDay())
                    ->where('percent_played', $i)
                    ->count();
            }
            Cache::put($yesterdays_percentage_views_key, $yesterdays_percentage_views, $expires_at);
        }

        $last_7_days_percentage_views_key = "$prefix.last_7_days_percentage_views_$user_id";
        if (Cache::has($last_7_days_percentage_views_key)) {
            $last_7_days_percentage_views = Cache::get($last_7_days_percentage_views_key);
        } else {
            $last_7_days_percentage_views = [];
            for ($i = 10; $i < 101; $i += 10) {
                $last_7_days_percentage_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->endOfDay())
                    ->where('percent_played', $i)
                    ->count();
            }
            Cache::put($last_7_days_percentage_views_key, $last_7_days_percentage_views, $expires_at);
        }

        $last_30_days_percentage_views_key = "$prefix.last_30_days_percentage_views_key_$user_id";
        if (Cache::has($last_30_days_percentage_views_key)) {
            $last_30_days_percentage_views = Cache::get($last_30_days_percentage_views_key);
        } else {
            $last_30_days_percentage_views = [];
            for ($i = 10; $i < 101; $i += 10) {
                $last_30_days_percentage_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->endOfDay())
                    ->where('percent_played', $i)
                    ->count();
            }
            Cache::put($last_30_days_percentage_views_key, $last_30_days_percentage_views, $expires_at);
        }

        $this_years_percentage_views_key = "$prefix.this_years_percentage_views_$user_id";
        if (Cache::has($this_years_percentage_views_key)) {
            $this_years_percentage_views = Cache::get($this_years_percentage_views_key);
        } else {
            $this_years_percentage_views = [];
            for ($i = 10; $i < 101; $i += 10) {
                $this_years_percentage_views[$i] = AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::parse('first day of January'))
                    ->where('created_at', '<=', Carbon::parse('first day of January next year')->subSecond())
                    ->where('percent_played', $i)
                    ->count();
            }
            Cache::put($this_years_percentage_views_key, $this_years_percentage_views, $expires_at);
        }

        /*
         *  Get all the statistics for the revenue of the last 30 days
         *  after they are cached only update the today's values
         *  or update however many days were missed.
         *  In case the last update was 30+ days ago, then get all the stats again
         *  and re-cache it forever
         */

        $this_months_revenue = [];

        $this_months_revenue_key = "$prefix.this_months_revenue_$user_id";
        if (Cache::has($this_months_revenue_key)) {
            $this_months_revenue = Cache::get($this_months_revenue_key);

            $last_update = new Carbon($this_months_revenue['last_update']);
            $now = Carbon::now();
            $diff_in_days = $last_update->diffInDays($now);
            $diff_in_minutes = $last_update->diffInMinutes($now);

            // updating of the arrays
            // if there is 1 or more day difference from when the array was last updated, then shift the array
            if ($diff_in_days >= 1 && $diff_in_days < 30) {
                // shift array however many days difference there was
                for ($x = 0; $x < $diff_in_days; $x++) {
                    for ($i = 0; $i < 30; $i++) {
                        $this_months_revenue[$i + 1] = $this_months_revenue[$i];
                    }
                }

                // echo "update because $diff_in_days since last update";
                // add however many days are different stats to the array again to get an updated statistics
                for ($i = 0; $i < $diff_in_days; $i++) {
                    $this_months_revenue[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }
                // this key stores the last time the array was updated
                $this_months_revenue['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_key, $this_months_revenue);

                // update the whole array
            } elseif ($diff_in_days > 29) {
                for ($i = 0; $i < 30; $i++) {
                    $this_months_revenue[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                        ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                        ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                        ->pluck('commission')
                        ->toArray());
                }

                $this_months_revenue['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_key, $this_months_revenue, $expires_at);

                // else update today's values if the last time updated is more than $expires_at_interval minutes
            } elseif ($diff_in_minutes >= $expires_at_interval) {
                // echo "update because $diff_in_minutes >= $expires_at_interval";
                // add today's stats to the array again to get an updated array
                $this_months_revenue[0] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays(0)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays(0)->endOfDay())
                    ->pluck('commission')
                    ->toArray());

                // this key stores the last time the array was updated
                $this_months_revenue['last_update'] = Carbon::now();
                Cache::forever($this_months_revenue_key, $this_months_revenue);
            }
            // echo "no update because diff_in_days $diff_in_days and diffinminutes $diff_in_minutes";
        } else {
            // echo "no cache is present";
            // If no cache is present
            // Revenue today, the past 7 days, and 30 days stored in one array
            for ($i = 0; $i < 30; $i++) {
                $this_months_revenue[$i] = array_sum(AffiliateVideoView::where('owner_id', $user->id)
                    ->where('created_at', '>=', Carbon::today()->subDays($i)->startOfDay())
                    ->where('created_at', '<=', Carbon::today()->subDays($i)->endOfDay())
                    ->pluck('commission')
                    ->toArray());
            }
            $this_months_revenue['last_update'] = Carbon::now();
            Cache::forever($this_months_revenue_key, $this_months_revenue, $expires_at);
        }

        $todays_revenue = $this->trimTrailingZeroes($this_months_revenue[0]);
        $yesterdays_revenue = $this->trimTrailingZeroes($this_months_revenue[1]);
        $last_7_days_revenue = $this->trimTrailingZeroes($todays_revenue + $yesterdays_revenue + $this_months_revenue[2]
            + $this_months_revenue[3] + $this_months_revenue[4] + $this_months_revenue[5] + $this_months_revenue[6]);
        $last_30_days_revenue = $this->trimTrailingZeroes(array_sum($this_months_revenue));
        $this_years_video_revenue = $this->trimTrailingZeroes($user->commissions_video);

        $revenue_chart = app()->chartjs
            ->name('revenueChart')
            ->type('bar')
            ->labels(['Today', $nameOfDayXDaysAgo[1], $nameOfDayXDaysAgo[2], $nameOfDayXDaysAgo[3],
                $nameOfDayXDaysAgo[4], $nameOfDayXDaysAgo[5], $nameOfDayXDaysAgo[6], ])
            ->datasets([
                [
                    'label'           => 'Revenue',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                    'borderWidth'     => 1,
                    'data'            => [$todays_revenue, $yesterdays_revenue, $this_months_revenue[2],
                        $this_months_revenue[3], $this_months_revenue[4],
                        $this_months_revenue[5], $this_months_revenue[6], ],
                ],
            ])
            ->options([
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero'   => true,
                                'maxTicksLimit' => '6',
                            ],
                        ],
                    ],
                ],
            ]);

        $adblock_usage_yes_key = "$prefix.adblock_usage_yes_$user_id";
        $adblock_usage_no_key = "$prefix.adblock_usage_no_$user_id";
        if (Cache::has($adblock_usage_yes_key)) {
            $adblock_usage_yes = Cache::get($adblock_usage_yes_key);
            $adblock_usage_no = Cache::get($adblock_usage_no_key);
        } else {
            $adblock_usage_yes = AffiliateVideoView::where('owner_id', $user->id)
                ->where('adblock', 1)
                ->count();

            $adblock_usage_no = AffiliateVideoView::where('owner_id', $user->id)
                ->where('adblock', 0)
                ->count();

            Cache::put($adblock_usage_yes_key, $adblock_usage_yes, $expires_at);
            Cache::put($adblock_usage_no_key, $adblock_usage_no, $expires_at);
        }

        // suppress errors because of division by 0
        $adblock_percentage = @round($adblock_usage_yes * 100 / ($adblock_usage_yes + $adblock_usage_no), 2);
        if (is_nan($adblock_percentage)) {
            $adblock_percentage = 0;
        }

        return view(
            'affiliate.statistics.video',
            compact(
                'page',
                'user',
                'line_chart',
                'bar_chart',
                'revenue_chart',
                'todays_views',
                'yesterdays_views',
                'last_7_days_views',
                'last_30_days_views',
                'this_years_views',
                'month_chart',
                'todays_percentage_views',
                'yesterdays_percentage_views',
                'last_7_days_percentage_views',
                'last_30_days_percentage_views',
                'this_years_percentage_views',
                'this_years_video_revenue',
                'views_last_30_days',
                'expires_at_interval',
                'this_months_revenue',
                'todays_revenue',
                'yesterdays_revenue',
                'last_7_days_revenue',
                'last_30_days_revenue',
                'adblock_usage_no',
                'adblock_usage_yes',
                'adblock_percentage'
            )
        );
    }

    /*
     * when the balance is very low, a scientific number is used
     * so make sure that up to 10 decimals are used and then trim
     * however many trailing zeroes there are.
     */

    public function trimTrailingZeroes($str)
    {
        return preg_replace('/(\.[0-9]+?)0*$/', '$1', number_format($str, 10));
    }
}
