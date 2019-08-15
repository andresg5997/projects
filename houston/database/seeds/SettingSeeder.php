<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('settings')->insert([

            [
                'name'       => 'general',
                'attributes' => json_encode([
                    'local_environment'   => 1,
                    'website_title'       => 'Clooud',
                    'main_title'          => 'Store your Media!',
                    'website_name'        => 'Clooud - Store your Media!',
                    'website_desc'        => 'Clooud Media - store your media anonymously, safely and fast while earning revenue!',
                    'website_keywords'    => 'cloud,clooud,media,uploads,sharing,money,safe,anonymous,fast,data,file,music,video,pictures,gallery',
                    'website_footer_text' => 'footer',
                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'email',
                'attributes' => json_encode([
                    'support_email'    => '',
                    'admin_email'      => '',
                    'no_reply_email'   => '',
                    'affiliate_email'  => '',
                    'dmca_email'       => '',
                    'sparkpost_secret' => encrypt(env('SPARKPOST_SECRET')),
                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'media',
                'attributes' => json_encode([

                    'auto_approve'                           => 0,
                    'remote_uploads'                         => 0,
                    'clone_uploads'                          => 0,
                    'guest_uploads'                          => 1,
                    'media_per_page'                         => 12,
                    'uploads_per_day'                        => 999,
                    'delete_after_x_days'                    => 30,
                    'uploads_per_day_per_guest'              => 20,
                    'max_file_upload_size_user'              => 5000,
                    'max_file_upload_size_guest'             => 300,
                    'max_amount_of_concurrent_uploads_user'  => 10,
                    'max_amount_of_concurrent_uploads_guest' => 4,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'comments',
                'attributes' => json_encode([

                    'active' => 1,

                    'comments_per_minutes' => 6,
                    'disqus' => '<div id="disqus_thread"></div>
<script>
(function() { // DON\'T EDIT BELOW THIS LINE
var d = document, s = d.createElement(\'script\');
s.src = \'https://clooud.disqus.com/embed.js\';
s.setAttribute(\'data-timestamp\', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>',

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'appearance',
                'attributes' => json_encode([

                    'theme' => 'blue',

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'points',
                'attributes' => json_encode([

                    'upload_media'   => 5,
                    'media_get_like' => 1,

                    'add_comment'      => 1,
                    'comment_get_like' => 1,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'social_keys',
                'attributes' => json_encode([

                    'active' => 0,

                    'facebook_client_id'     => env('FACEBOOK_CLIENT_ID'),
                    'facebook_client_secret' => encrypt(env('FACEBOOK_SECRET')),

                    'twitter_client_id'     => env('TWITTER_CLIENT_ID'),
                    'twitter_client_secret' => encrypt(env('TWITTER_SECRET')),

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'social_links',
                'attributes' => json_encode([

                    'facebook'  => 'https://www.facebook.com/Clooud-Media-1326126244143374/',
                    'twitter'   => 'https://twitter.com/ClooudMedia',
                    'instagram' => '',

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'storage',
                'attributes' => json_encode([

                    'keep_copy'  => 0,

                    's3_active'  => 0,
                    's3_region'  => '',
                    's3_bucket'  => '',
                    's3_key'     => '',
                    's3_secret'  => '',

                    'dropbox_authorization_token'   => '',
                    'dropbox_active'                => 0,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'captcha',
                'attributes' => json_encode([

                    'active' => 0,

                    'captcha_secret'  => encrypt(env('NOCAPTCHA_SECRET')),
                    'captcha_sitekey' => env('NOCAPTCHA_SITEKEY'),

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'tags',
                'attributes' => json_encode([

                    'max_tags_per_media' => 20,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'affiliate',
                'attributes' => json_encode([

                    'active' => 0,

                    'country_group_1_list' => 'United States,United Kingdom,Canada,Australia',
                    'country_group_2_list' => 'Germany,Sweden,Norway,Denmark,Finland',
                    'country_group_3_list' => 'Japan,Netherlands,Switzerland,Austria,Italy,France,Spain,Poland,South Africa',
                    'country_group_4_list' => 'Belgium,Portugal,Slovakia,Singapore,All Other Countries',

                    'amount_for_country_group_1' => 50,
                    'amount_for_country_group_2' => 40,
                    'amount_for_country_group_3' => 30,
                    'amount_for_country_group_4' => 20,

                    'image_multiplier' => 0.3,
                    'audio_multiplier' => 0.8,

                    'image_duration_for_commission' => 3,

                    'payout_minimum' => 20,

                    'referral_multiplier' => 0.3,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'cache',
                'attributes' => json_encode([

                    'expires_at_interval'=> 15,

                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'analytics',
                'attributes' => json_encode([

                    'active' => 0,

                    'google_analytics_id' => 'UA-91752585-1',
                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'adblock',
                'attributes' => json_encode([

                    'active' => 0,

                    'popup_time'           => 10,
                    'notification_message' => "Hello there! In order to serve you even better, please support us by turning off your AdBlocker. The fair and ethically right usage of ads is what we strive for. <br><br>We don\'t like ads either. <i class=\"fa fa-thumbs-o-down\"></i>",
                ]),
                'created_at' => Carbon::now(),
            ],

            [
                'name'       => 'advertisement',
                'attributes' => json_encode([

                    'active' => 0,

                    'home_page_ad_slot'                => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1133065741007668"
     data-ad-slot="8541467147"
     data-ad-format="auto"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>',
                    'media_index_page_main_ad_slot'    => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1133065741007668"
     data-ad-slot="9887102744"
     data-ad-format="auto"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>',
                    'media_index_page_sidebar_ad_slot' => '<script data-cfasync="false" type="text/javascript" src="https://www.tradeadexchange.com/a/display.php?r=1567005"></script>',
                    'embed_page_interstitial'          => '<script data-cfasync="false" type="text/javascript" src="https://www.tradeadexchange.com/a/display.php?r=1566965"></script>',
                    'embed_page_pop_under'             => '<script data-cfasync="false" type="text/javascript" src="https://www.tradeadexchange.com/a/display.php?r=1566937"></script>',
                ]),
                'created_at' => Carbon::now(),
            ],

        ]);
    }
}
