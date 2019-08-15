<?php

namespace App\Providers;

use App\Setting;
use Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // overwrite config files' values
        $results = \DB::select( \DB::raw("select version()") );
        $mysql_version =  $results[0]->{'version()'};
        $mariadb_version = '';

        if (strpos($mysql_version, 'Maria') !== false) {
            $mariadb_version = $mysql_version;
            $mysql_version = '';
        }

        if(version_compare($mariadb_version, '10.2.2-MariaDB') >= 0  || version_compare($mysql_version, '5.7.9') >= 0) {
            $charset = 'utf8mb4';
            $collation = 'utf8mb4_unicode_ci';

            config([

                'database.connections.mysql.charset'   => $charset,
                'database.connections.mysql.collation' => $collation,

            ]);
        }

        if (\DB::connection()->getDatabaseName() && Schema::hasTable('settings') && \DB::table('settings')->count()) {
            $setting = new Setting();

            // Cache Settings - needs to be the first config, because these settings get cached as well
            config()->set('expires_at_interval', $setting->gets('cache')->expires_at_interval);

            // General Settings
            config()->set('local_environment', $setting->gets('general')->local_environment);
            config()->set('website_title', $setting->gets('general')->website_title);
            config()->set('main_title', $setting->gets('general')->main_title);
            config()->set('website_name', $setting->gets('general')->website_title ." - ". $setting->gets('general')->main_title);
            config()->set('website_desc', $setting->gets('general')->website_desc);
            config()->set('website_keywords', $setting->gets('general')->website_keywords);
            config()->set('website_footer_text', $setting->gets('general')->website_footer_text);

            // Email Settings
            config()->set('sparkpost_secret', ($setting->gets('email')->sparkpost_secret == '') ? '' : decrypt($setting->gets('email')->sparkpost_secret));
            config()->set('support_email', $setting->gets('email')->support_email);
            config()->set('admin_email', $setting->gets('email')->admin_email);
            config()->set('no_reply_email', $setting->gets('email')->no_reply_email);
            config()->set('affiliate_email', $setting->gets('email')->affiliate_email);
            config()->set('dmca_email', $setting->gets('email')->dmca_email);

            // Media Settings
            config()->set('media_per_page', $setting->gets('media')->media_per_page);
            config()->set('uploads_per_day', $setting->gets('media')->uploads_per_day);
            config()->set('uploads_per_day_per_guest', $setting->gets('media')->uploads_per_day_per_guest);
            config()->set('auto_approve', $setting->gets('media')->auto_approve);
            config()->set('remote_uploads', $setting->gets('media')->remote_uploads);
            config()->set('clone_uploads', $setting->gets('media')->clone_uploads);
            config()->set('guest_uploads', $setting->gets('media')->guest_uploads);
            config()->set('delete_after_x_days', $setting->gets('media')->delete_after_x_days);
            config()->set('max_file_upload_size_user', $setting->gets('media')->max_file_upload_size_user);
            config()->set('max_file_upload_size_guest', $setting->gets('media')->max_file_upload_size_guest);
            config()->set('max_amount_of_concurrent_uploads_user', $setting->gets('media')->max_amount_of_concurrent_uploads_user);
            config()->set('max_amount_of_concurrent_uploads_guest', $setting->gets('media')->max_amount_of_concurrent_uploads_guest);

            // Comments Settings
            config()->set('comments_active', $setting->gets('comments')->active);
            config()->set('comments_per_minutes', $setting->gets('comments')->comments_per_minutes);
            config()->set('disqus', $setting->gets('comments')->disqus);

            // Tags Settings
            config()->set('max_tags_per_media', $setting->gets('tags')->max_tags_per_media);

            // Points Settings
            config()->set('add_comment', $setting->gets('points')->add_comment);
            config()->set('comment_get_like', $setting->gets('points')->comment_get_like);
            config()->set('upload_media', $setting->gets('points')->upload_media);
            config()->set('media_get_like', $setting->gets('points')->media_get_like);

            // Affiliate Settings
            config()->set('affiliate_active', $setting->gets('affiliate')->active);
            config()->set('amount_for_country_group_1', $setting->gets('affiliate')->amount_for_country_group_1);
            config()->set('amount_for_country_group_2', $setting->gets('affiliate')->amount_for_country_group_2);
            config()->set('amount_for_country_group_3', $setting->gets('affiliate')->amount_for_country_group_3);
            config()->set('amount_for_country_group_4', $setting->gets('affiliate')->amount_for_country_group_4);
            config()->set('country_group_1_list', $setting->gets('affiliate')->country_group_1_list);
            config()->set('country_group_2_list', $setting->gets('affiliate')->country_group_2_list);
            config()->set('country_group_3_list', $setting->gets('affiliate')->country_group_3_list);
            config()->set('country_group_4_list', $setting->gets('affiliate')->country_group_4_list);
            config()->set('image_multiplier', $setting->gets('affiliate')->image_multiplier);
            config()->set('audio_multiplier', $setting->gets('affiliate')->audio_multiplier);
            config()->set('payout_minimum', $setting->gets('affiliate')->payout_minimum);
            config()->set('referral_multiplier', $setting->gets('affiliate')->referral_multiplier);
            config()->set('image_duration_for_commission', $setting->gets('affiliate')->image_duration_for_commission);

            // Social Auth Settings
            config()->set('social_keys_active', $setting->gets('social_keys')->active);

            config()->set('facebook_client_id', $setting->gets('social_keys')->facebook_client_id);
            config()->set('facebook_client_secret', ($setting->gets('social_keys')->facebook_client_secret == '') ? '' : decrypt($setting->gets('social_keys')->facebook_client_secret));

            config()->set('twitter_client_id', $setting->gets('social_keys')->twitter_client_id);
            config()->set('twitter_client_secret', ($setting->gets('social_keys')->twitter_client_secret == '') ? '' : decrypt($setting->gets('social_keys')->twitter_client_secret));

            config()->set('facebook_login_url', route('callback', 'facebook'));
            config()->set('twitter_login_url', route('callback', 'twitter'));

            // Social Links Settings
            config()->set('facebook', $setting->gets('social_links')->facebook);
            config()->set('twitter', $setting->gets('social_links')->twitter);
            config()->set('instagram', $setting->gets('social_links')->instagram);

            // Social Links Settings
            config()->set('keep_copy', $setting->gets('storage')->keep_copy);
            config()->set('s3_active', $setting->gets('storage')->s3_active);
            config()->set('s3_bucket', $setting->gets('storage')->s3_bucket);
            config()->set('s3_region', $setting->gets('storage')->s3_region);
            config()->set('s3_key', $setting->gets('storage')->s3_key);
            config()->set('s3_secret', ($setting->gets('storage')->s3_secret == '' ? '' : decrypt($setting->gets('storage')->s3_secret)));

            config()->set('dropbox_active', $setting->gets('storage')->dropbox_active);
            config()->set('dropbox_authorization_token', ($setting->gets('storage')->dropbox_authorization_token == '' ? '' : decrypt($setting->gets('storage')->dropbox_authorization_token)));

            // Captcha Settings
            config()->set('captcha_active', $setting->gets('captcha')->active);
            config()->set('captcha_secret', ($setting->gets('captcha')->captcha_secret == '' ? '' : decrypt($setting->gets('captcha')->captcha_secret)));
            config()->set('captcha_sitekey', $setting->gets('captcha')->captcha_sitekey);

            // Appearance Theme Settings
            config()->set('theme', $setting->gets('appearance')->theme);

            // Google Analytics Settings
            config()->set('analytics_active', $setting->gets('analytics')->active);
            config()->set('google_analytics_id', $setting->gets('analytics')->google_analytics_id);

            // Advertisement Settings
            config()->set('advertisements_active', $setting->gets('advertisement')->active);
            config()->set('home_page_ad_slot', $setting->gets('advertisement')->home_page_ad_slot);
            config()->set('media_index_page_main_ad_slot', $setting->gets('advertisement')->media_index_page_main_ad_slot);
            config()->set('media_index_page_sidebar_ad_slot', $setting->gets('advertisement')->media_index_page_sidebar_ad_slot);
            config()->set('embed_page_interstitial', $setting->gets('advertisement')->embed_page_interstitial);
            config()->set('embed_page_pop_under', $setting->gets('advertisement')->embed_page_pop_under);

            // Advertisement Settings
            config()->set('adblock_active', $setting->gets('adblock')->active);
            config()->set('adblock_popup_time', $setting->gets('adblock')->popup_time);
            config()->set('adblock_notification_message', $setting->gets('adblock')->notification_message);

        }

        config([

            'chatter.headline' => 'Welcome to '.explode('.', ucfirst(request()->getHost()))[0],

            'services.sparkpost.secret' => config('sparkpost_secret'),

            'services.facebook.client_id'     => config('facebook_client_id'),
            'services.facebook.client_secret' => config('facebook_client_secret'),
            'services.facebook.redirect'      => config('facebook_login_url'),

            'services.twitter.client_id'     => config('twitter_client_id'),
            'services.twitter.client_secret' => config('twitter_client_secret'),
            'services.twitter.redirect'      => config('twitter_login_url'),

            'captcha.secret'  => config('captcha_secret'),
            'captcha.sitekey' => config('captcha_sitekey'),

            'mail.from.name'    => config('website_title'),
            'mail.from.address' => config('admin_email'),

            // backup email
            'laravel-backup.notifications.mail.to' => config('admin_email'),

            // AWS credentials
            'filesystems.disks.s3.key'     => config('s3_key'),
            'filesystems.disks.s3.secret'  => config('s3_secret'),
            'filesystems.disks.s3.region'  => config('s3_region'),
            'filesystems.disks.s3.bucket'  => config('s3_bucket'),

            // Dropbox Token
            'filesystems.disks.dropbox.authorizationToken'  => config('dropbox_authorization_token'),

        ]);

        view()->share('website_desc', config('website_desc'));

        view()->share('website_keywords', config('website_keywords'));
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
