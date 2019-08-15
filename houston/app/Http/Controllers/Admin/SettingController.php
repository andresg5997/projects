<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use File;
use DB;
use Storage;
use View;

class SettingController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'settings');
    }

    //
    public function media()
    {
        $ffmpeg = command_exist('ffmpeg --help');
        $handbrake = command_exist('HandBrakeCLI --help');
        $youtube_dl = command_exist('youtube-dl --help');
        $gdrive = command_exist('gdrive help');

        $shell_exec = isEnabled('shell_exec');
        $exec = isEnabled('exec');

        $attributes = json_decode(Setting::where('name', 'media')->value('attributes'));

        return view('admin.settings.media', compact('attributes', 'ffmpeg', 'handbrake', 'youtube_dl', 'gdrive', 'shell_exec', 'exec'));
    }

    public function updateMedia(Request $request)
    {
        if ($request->input('auto_approve') !== null) {
            $auto_approve = 1;
        } else {
            $auto_approve = 0;
        }

        if ($request->input('remote_uploads') !== null) {
            $remote_uploads = 1;
        } else {
            $remote_uploads = 0;
        }

        if ($request->input('clone_uploads') !== null) {
            $clone_uploads = 1;
        } else {
            $clone_uploads = 0;
        }

        if ($request->input('guest_uploads') !== null) {
            $guest_uploads = 1;
        } else {
            $guest_uploads = 0;
        }

        if ($request->input('editor_uploads') !== null) {
            $editor_uploads = 1;
        } else {
            $editor_uploads = 0;
        }

        Setting::where('name', 'media')->update([

            'attributes' => json_encode([
                'auto_approve'                           => $auto_approve,
                'remote_uploads'                         => $remote_uploads,
                'clone_uploads'                          => $clone_uploads,
                'guest_uploads'                          => $guest_uploads,
                'editor_uploads'                         => $editor_uploads,
                'delete_after_x_days'                    => $request->input('delete_after_x_days'),
                'max_tags_per_media'                     => $request->input('max_tags_per_media'),
                'media_per_page'                         => $request->input('media_per_page'),
                'uploads_per_day'                        => $request->input('uploads_per_day'),
                'uploads_per_day_per_guest'              => $request->input('uploads_per_day_per_guest'),
                'max_file_upload_size_user'              => $request->input('max_file_upload_size_user'),
                'max_file_upload_size_guest'             => $request->input('max_file_upload_size_guest'),
                'max_amount_of_concurrent_uploads_user'  => $request->input('max_amount_of_concurrent_uploads_user'),
                'max_amount_of_concurrent_uploads_guest' => $request->input('max_amount_of_concurrent_uploads_guest'),
            ]),
        ]);

        \Cache::forget('attributes_media');

        flash('Media Settings have been updated!', 'success');

        return back();
    }

    public function comments()
    {
        $attributes = json_decode(Setting::where('name', 'comments')->value('attributes'));

        return view('admin.settings.comments', compact('attributes'));
    }

    public function updateComments(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'comments')->update([

            'attributes' => json_encode([

                'active' => $active,

                'comments_per_minutes' => $request->input('comments_per_minutes'),
                'disqus'               => $request->input('disqus')
            ]),
        ]);

        \Cache::forget('attributes_comments');

        flash('Comments Settings have been updated!', 'success');

        return back();
    }

    public function email()
    {
        $attributes = json_decode(Setting::where('name', 'email')->value('attributes'));

        return view('admin.settings.email', compact('attributes'));
    }

    public function updateEmail(Request $request)
    {
        Setting::where('name', 'email')->update([

            'attributes' => json_encode([

                'sparkpost_secret' => encrypt($request->input('sparkpost_secret')),

                'support_email'   => $request->input('support_email'),
                'admin_email'     => $request->input('admin_email'),
                'no_reply_email'  => $request->input('no_reply_email'),
                'affiliate_email' => $request->input('affiliate_email'),
                'dmca_email'      => $request->input('dmca_email'),
            ]),
        ]);

        \Cache::forget('attributes_email');

        flash('Email Settings have been updated!', 'success');

        return back();
    }

    public function affiliate()
    {
        $attributes = json_decode(Setting::where('name', 'affiliate')->value('attributes'));

        return view('admin.settings.affiliate', compact('attributes'));
    }

    public function updateAffiliate(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'affiliate')->update([

            'attributes' => json_encode([

                'active' => $active,

                'amount_for_country_group_1' => $request->input('amount_for_country_group_1'),
                'amount_for_country_group_2' => $request->input('amount_for_country_group_2'),
                'amount_for_country_group_3' => $request->input('amount_for_country_group_3'),
                'amount_for_country_group_4' => $request->input('amount_for_country_group_4'),

                'country_group_1_list' => $request->input('country_group_1_list'),
                'country_group_2_list' => $request->input('country_group_2_list'),
                'country_group_3_list' => $request->input('country_group_3_list'),
                'country_group_4_list' => $request->input('country_group_4_list'),

                'image_multiplier' => $request->input('image_multiplier'),
                'audio_multiplier' => $request->input('image_multiplier'),

                'payout_minimum' => $request->input('payout_minimum'),

                'referral_multiplier' => $request->input('payout_minimum'),

                'image_duration_for_commission' => $request->input('payout_minimum'),

            ]),
        ]);

        \Cache::forget('attributes_affiliate');

        flash('Affiliate Settings have been updated!', 'success');

        return back();
    }

    public function analytics()
    {
        $attributes = json_decode(Setting::where('name', 'analytics')->value('attributes'));

        return view('admin.settings.analytics', compact('attributes'));
    }

    public function updateAnalytics(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'analytics')->update([

            'attributes' => json_encode([

                'active' => $active,

                'google_analytics_id' => $request->input('google_analytics_id')
            ]),
        ]);

        \Cache::forget('attributes_analytics');

        flash('Analytics Settings have been updated!', 'success');

        return back();
    }

    public function advertisements()
    {
        $attributes = json_decode(Setting::where('name', 'advertisement')->value('attributes'));

        return view('admin.settings.advertisements', compact('attributes'));
    }

    public function updateAdvertisements(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'advertisement')->update([

            'attributes' => json_encode([

                'active' => $active,

                'home_page_ad_slot'                => $request->input('home_page_ad_slot'),
                'media_index_page_main_ad_slot'    => $request->input('media_index_page_main_ad_slot'),
                'media_index_page_sidebar_ad_slot' => $request->input('media_index_page_sidebar_ad_slot'),
                'embed_page_interstitial'          => $request->input('embed_page_interstitial'),
                'embed_page_pop_under'             => $request->input('embed_page_pop_under'),
            ]),
        ]);

        \Cache::forget('attributes_advertisement');

        flash('Advertisements Settings have been updated!', 'success');

        return back();
    }

    public function general()
    {
        $attributes = json_decode(Setting::where('name', 'general')->value('attributes'));

        return view('admin.settings.general', compact('attributes'));
    }

    public function updateGeneral(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
            setEnvironmentValue('APP_ENV', 'app.env', 'local');
            setEnvironmentValue('APP_DEBUG', 'app.debug', true);
        } else {
            $active = 0;
            setEnvironmentValue('APP_ENV', 'app.env', 'production');
            setEnvironmentValue('APP_DEBUG', 'app.debug', 'false');
        }

        Setting::where('name', 'general')->update([

            'attributes' => json_encode([

                'local_environment'  => $active,
                'main_title'         => $request->input('main_title'), // slogan
                'website_title'      => $request->input('website_title'),
                'website_name'       => $request->input('website_title').' - '.$request->input('main_title'),
                'website_desc'       => $request->input('website_desc'),
                'website_keywords'   => $request->input('website_keywords'),
                'website_footer_text' => $request->input('website_footer_text'),
            ]),
        ]);
        $logo = $request->file('logo');
        $brand_logo = $request->file('brand');
        $favicon = $request->file('favicon');

        if ($logo) {
            if(\App::environment('production')){
                Storage::disk('s3')->put('images/logo.png',  File::get($logo));;
            }
            else{
                Storage::disk('assets')->put('images/logo.png',  File::get($logo));
            }
        }

        if ($brand_logo) {
            if(\App::environment('production')){
                Storage::disk('s3')->put('images/brand.png',  File::get($logo));;
            }
            else{
                Storage::disk('assets')->put('images/brand.png',  File::get($logo));
            }
        }

        if ($favicon) {
            if(\App::environment('production')){
                Storage::disk('s3')->put('images/favicons/favicon.png',  File::get($logo));;
            }
            else{
                Storage::disk('assets')->put('images/favicons/favicon.png',  File::get($logo));
            }
        }

        \Cache::forget('attributes_general');

        flash('General Settings have been updated!', 'success');

        return back();
    }

    public function cache()
    {
        $attributes = json_decode(Setting::where('name', 'cache')->value('attributes'));

        return view('admin.settings.cache', compact('attributes'));
    }

    public function updateCache(Request $request)
    {
        Setting::where('name', 'cache')->update([

            'attributes' => json_encode([

                'expires_at_interval' => $request->input('expires_at_interval'),
            ]),
        ]);

        \Cache::forget('attributes_cache');

        flash('Cache Settings have been updated!', 'success');

        return back();
    }

    public function points()
    {
        $attributes = json_decode(Setting::where('name', 'points')->value('attributes'));

        return view('admin.settings.points', compact('attributes'));
    }

    public function updatePoints(Request $request)
    {
        Setting::where('name', 'points')->update([

            'attributes' => json_encode([

                'upload_media'  => $request->input('upload_media'),
                'media_get_like' => $request->input('media_get_like'),

                'add_comment'     => $request->input('add_comment'),
                'comment_get_like' => $request->input('comment_get_like'),
            ]),
        ]);

        \Cache::forget('attributes_points');

        flash('Points Settings have been updated!', 'success');

        return back();
    }

    public function socialLogin()
    {
        $attributes = json_decode(Setting::where('name', 'social_keys')->value('attributes'));

        return view('admin.settings.social_login', compact('attributes'));
    }

    public function updateSocialLogin(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'social_keys')->update([

            'attributes' => json_encode([

                'active' => $active,

                'facebook_client_id'     => $request->input('facebook_client_id'),
                'facebook_client_secret' => encrypt($request->input('facebook_client_secret')),

                'twitter_client_id'     => $request->input('twitter_client_id'),
                'twitter_client_secret' => encrypt($request->input('twitter_client_secret')),
            ]),
        ]);

        \Cache::forget('attributes_social_keys');

        flash('Social Keys have been updated!', 'success');

        return back();
    }

    public function socialLinks()
    {
        $attributes = json_decode(Setting::where('name', 'social_links')->value('attributes'));

        return view('admin.settings.social_links', compact('attributes'));
    }

    public function updateSocialLinks(Request $request)
    {
        Setting::where('name', 'social_links')->update([

            'attributes' => json_encode([

                'facebook' => $request->input('facebook'),
                'twitter'  => $request->input('twitter'),
                'instagram' => $request->input('instagram'),

            ]),
        ]);

        \Cache::forget('attributes_social_links');

        flash('Social Links have been updated!', 'success');

        return back();
    }

    public function captcha()
    {
        $attributes = json_decode(Setting::where('name', 'captcha')->value('attributes'));

        return view('admin.settings.captcha', compact('attributes'));
    }

    public function updateCaptcha(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'captcha')->update([

            'attributes' => json_encode([

                'active' => $active,

                'captcha_secret' => encrypt($request->input('captcha_secret')),
                'captcha_sitekey' => $request->input('captcha_sitekey'),
            ]),
        ]);

        \Cache::forget('attributes_captcha');

        flash('Captcha settings have been updated!', 'success');

        return back();
    }

    public function tags()
    {
        $attributes = json_decode(Setting::where('name', 'tags')->value('attributes'));

        return view('admin.settings.tags', compact('attributes'));
    }

    public function updateTags(Request $request)
    {
        Setting::where('name', 'tags')->update([

            'attributes' => json_encode([

                'max_tags_per_media' => $request->input('max_tags_per_media'),
            ]),
        ]);

        \Cache::forget('attributes_tags');

        flash('Tag settings have been updated!', 'success');

        return back();
    }

    public function adblock()
    {
        $attributes = json_decode(Setting::where('name', 'adblock')->value('attributes'));

        return view('admin.settings.adblock', compact('attributes'));
    }

    public function updateAdblock(Request $request)
    {
        if ($request->input('active') !== null) {
            $active = 1;
        } else {
            $active = 0;
        }

        Setting::where('name', 'adblock')->update([

            'attributes' => json_encode([

                'active' => $active,

                'popup_time'           => $request->input('popup_time'),
                'notification_message' => $request->input('notification_message'),
            ]),
        ]);

        \Cache::forget('attributes_adblock');

        flash('Adblock settings have been updated!', 'success');

        return back();
    }

    public function storage()
    {
        $attributes = json_decode(Setting::where('name', 'storage')->value('attributes'));

        return view('admin.settings.storage', compact('attributes'));
    }

    public function updateStorage(Request $request)
    {
        if ($request->input('s3_active') !== null) {
            $s3_active = 1;
            $dropbox_active = 0;
        } elseif ($request->input('dropbox_active') !== null) {
            $dropbox_active = 1;
            $s3_active = 0;
        } else {
            $s3_active = 0;
            $dropbox_active = 0;
        }

        if ($request->input('keep_copy') !== null) {
            $active_copy = 1;
        } else {
            $active_copy = 0;
        }

        Setting::where('name', 'storage')->update([

            'attributes' => json_encode([

                'keep_copy' => $active_copy,

                's3_active' => $s3_active,
                's3_region' => $request->input('s3_region'),
                's3_bucket' => $request->input('s3_bucket'),
                's3_key'    => $request->input('s3_key'),
                's3_secret' => encrypt($request->input('s3_secret')),

                'dropbox_active'              => $dropbox_active,
                'dropbox_authorization_token' => encrypt($request->input('dropbox_authorization_token')),

            ]),
        ]);

        \Cache::forget('attributes_storage');

        flash('Storage settings have been updated!', 'success');

        return back();
    }

    public function backups()
    {
        return view('admin.settings.advanced.backups');
    }

    public function logs()
    {
        return view('admin.settings.advanced.logs');
    }

    public function recommended()
    {
        $attributes = json_decode(Setting::where('name', 'media')->value('attributes'));

        $ffmpeg = command_exist('ffmpeg --help');
        $handbrake = command_exist('HandBrakeCLI --help');
        $youtube_dl = command_exist('youtube-dl --help');
        $gdrive = command_exist('gdrive help');
        $phantomjs = command_exist('phantomjs --help');

        $shell_exec = isEnabled('shell_exec');
        $exec = isEnabled('exec');

        $results = DB::select( DB::raw("select version()") );
        $mysql_version =  $results[0]->{'version()'};
        $mariadb_version = '';

        if (strpos($mysql_version, 'Maria') !== false) {
            $mariadb_version = $mysql_version;
            $mysql_version = '';
        }

        return view('admin.settings.advanced.recommended', compact('attributes', 'ffmpeg', 'handbrake', 'youtube_dl', 'gdrive', 'shell_exec', 'exec', 'mysql_version', 'mariadb_version', 'phantomjs'));
    }
}
