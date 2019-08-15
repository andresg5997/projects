<?php

use App\Team;
Auth::routes();

Route::get('/sparkpost', function () {
     $data = [
            'player' => \App\Player::first(),
            'email' => 'asdasd@asd.com',
            'team' => Team::find(1)
        ];

        return view('emails.invite_parent')->with('player', $data['player'])->with('team', $data['team'])->with('email', $data['email']);

        // $mail = Mail::send('emails.team_invitation', $data, function ($message) use ($data) {
        //     $message
        //         ->from(env('MAIL_USERNAME'), config('website_title'))
        //         ->to($data['email'])
        //         ->subject("New Event: {$data['event']->name} on " . date('M d, h:i A', strtotime($data['event']->date)));
        // });
        if($mail){
            return true;
        }
        return false;
});

Route::get('/check-php-ini', function () {
    return dd(phpinfo());
});

Route::get('confirm/{token}', ['as' => 'confirm.email', 'uses' => 'Auth\LoginController@confirmEmail']);
Route::get('/openload/text-file/links', ['as' => 'clone.openload', 'uses' => 'OpenloadController@index']);
Route::post('/openload/text-file/links', ['as' => 'clone.openload.files', 'uses' => 'OpenloadController@cloneFiles']);

Route::get('/openload/shrink/text-file/', ['as' => 'openload.shrink.text-file', 'uses' => 'OpenloadController@shrink']);

Route::get('/redirect/{provider}', ['as' => 'redirect', 'uses' => 'SocialAuthController@redirect']);
Route::get('/callback/{provider}', ['as' => 'callback', 'uses' => 'SocialAuthController@callback']);

    // Team Routes


Route::group(['namespace' => 'Team', 'middleware' => ['web', 'auth']], function () {
    Route::resource('/teams', 'TeamController');
    Route::get('/teams/{id}/manage', ['as' => 'teams.manage', 'uses' => 'TeamController@manage']);
    Route::post('teams/{id}/uploadPicture', ['as' => 'teams.uploadPicture', 'uses' => 'TeamController@uploadPicture']);
    Route::post('teams/{id}/uploadArchives', ['as' => 'teams.uploadArchives', 'uses' => 'TeamController@uploadArchives']);
    Route::post('teams/{id}/importRoster', ['as' => 'teams.importRoster', 'uses' => 'RosterController@importRoster']);
    Route::get('teams/{id}/teams', ['as' => 'teams.getTeams', 'uses' => 'ScheduleController@getTeams']);
    Route::get('getTeam/{id}', ['as' => 'teams.getTeam', 'uses' => 'TeamController@getTeam']);
    Route::delete('archives/{archive}', 'TeamController@deleteArchive');

    // Coach routes
    Route::resource('/teams/{id}/coach', 'CoachController');

    // Schedule routes
    Route::resource('/teams/{id}/schedule', 'ScheduleController');
    Route::post('/teams/{id}/postSchedule', ['uses' => 'ScheduleController@postStore', 'as' => 'schedule.postStore']);
    Route::get('export/{calendar}/{event_id}', 'ScheduleController@exportEventTo');

    // Roster routes
    Route::resource('/teams/{id}/roster', 'RosterController');
    Route::get('/teams/{id}/ajax', ['uses' => 'RosterController@ajax', 'as' => 'roster.ajax']);
    Route::get('roster/template', ['uses' => 'RosterController@downloadTemplate', 'as' => 'roster.template']);
    Route::get('teams/{id}/getSliderImages', ['uses' => 'TeamController@getSliderImages', 'as' => 'teams.getSliderImages']);
    Route::get('teams/{id}/dashboardEvents', ['uses' => 'TeamController@dashboardEvents', 'as' => 'teams.dashboardEvents']);
    Route::get('teams/{id}/getEvents', ['uses' => 'TeamController@getEvents', 'as' => 'teams.getEvents']);
    Route::get('teams/{id}/getMedia', 'TeamController@getMediaPosts');

    // Assignments routes
    Route::resource('/teams/{id}/assignments', 'AssignmentsController');
    Route::get('/teams/{id}/getAssignments', 'AssignmentsController@ajax');

    // Availability routes
    Route::resource('/teams/{id}/availability', 'AvailabilityController');
    Route::get('/teams/{id}/getAvailability', 'AvailabilityController@ajax');
    Route::post('/teams/{id}/markAllAvailable', 'AvailabilityController@markAll');

    // Events routes
    Route::resource('/teams/{id}/events', 'EventController');

    // LineUp routes
    Route::resource('/teams/{id}/lineup', 'LineUpController');
    Route::get('/teams/{id}/getLineUp', 'LineUpController@ajax');

    Route::post('updateEvent', ['uses' => 'EventController@updateEvent', 'as' => 'schedule.updateEvent']);
});


// // Media Routes
// Route::get('/media', function(){
//     return view('media.index');
// })->name('media.index');


// Post Routes
Route::resource('posts', 'PostsController');
Route::post('post/store', ['as' => 'post.store', 'uses' => 'PostsController@storePost']);
Route::put('putLike', ['as' => 'posts.like', 'uses' => 'PostsController@putLike']);
Route::get('postsTeam/{id}', ['as' => 'posts.team', 'uses' => 'PostsController@dashboard']);
Route::put('putComment', ['as' => 'posts.comment', 'uses' => 'PostsController@putComment']);
Route::put('updateComment', ['as' => 'posts.comment.update', 'uses' => 'PostsController@updateComment']);
Route::delete('deleteComment', ['as' => 'posts.comment.delete', 'uses' => 'PostsController@deleteComment']);
Route::put('updatePost', ['as' => 'posts.post.update', 'uses' => 'PostsController@updatePost']);
Route::delete('deletePost', ['as' => 'posts.post.delete', 'uses' => 'PostsController@deletePost']);
Route::delete('deleteMedia/{slug?}', ['as' => 'posts.deleteMedia', 'uses' => 'PostsController@deleteMedia']);
Route::post('teams/{id}/invite', 'InviteController@invite');
Route::put('updatePicture', ['uses' => 'Team\RosterController@updatePicture', 'as' => 'roster.updatePicture']);

// Home Routes
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::post('/', ['as' => 'home.clear.session', 'uses' => 'HomeController@clearSession']);
Route::get('changeGrid/{grids}', ['as' => 'media.changeGrid', 'uses' => 'MediaController@changeGrid']);

// Pages Routes
Route::get('supported-media-hosts', ['as' => 'supported-media-hosts', 'uses' => 'HomeController@supportedMediaHosts']);

// Search Routes
Route::get('search', ['as' => 'search', 'uses' => 'HomeController@search']);

// Order By Routes
Route::get('popular', ['as' => 'media.mostPopular', 'uses' => 'HomeController@popular']);
Route::get('likes', ['as' => 'media.mostLikes', 'uses' => 'HomeController@likes']);
Route::get('comments', ['as' => 'media.mostComments', 'uses' => 'HomeController@comments']);
Route::get('random', ['as' => 'media.random', 'uses' => 'HomeController@random']);

// Categories Routes
Route::get('categories', ['as' => 'categories', 'uses' => 'CategoryController@index']);
Route::get('category/{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
Route::get('category/{slug}/popular', ['as' => 'category.show.mostPopular', 'uses' => 'CategoryController@popular']);
Route::get('category/{slug}/likes', ['as' => 'category.show.mostLikes', 'uses' => 'CategoryController@likes']);
Route::get('category/{slug}/comments', ['as' => 'category.show.mostComments', 'uses' => 'CategoryController@comments']);
Route::get('category/{slug}/random', ['as' => 'category.show.random', 'uses' => 'CategoryController@random']);

// Tag Routes
Route::get('tag/{slug}', ['as' => 'tag.show', 'uses' => 'TagController@show']);

// Show Media Routes
Route::get('m/{key}', ['as' => 'media.show.with_key', 'uses' => 'MediaController@showShortUrl']);
Route::get('embed/{key}', ['as' => 'media.show.embed', 'uses' => 'MediaController@showEmbed']);
Route::get('media/{slug}', ['as' => 'media.show', 'uses' => 'MediaController@show']);
Route::post('media/{slug}', ['as' => 'media.password.check', 'uses' => 'MediaController@passwordCheck']);

Route::post('stream-ol/{key}', ['as' => 'media.get.openload', 'uses' => 'MediaController@getOpenloadDownloadURL']);

// Add Play to Media
Route::post('media/play/{key}', ['as' => 'media.add.play', 'uses' => 'MediaController@addMediaPlay']);

// Flags/Report
Route::post('media/flag', ['as' => 'media.flag', 'uses' => 'MediaController@flag']);

// Show Profile Routes
Route::get('user/{username}', ['as' => 'user.profile.index', 'uses' => 'ProfileController@index']);
Route::get('user/{username}/likes', ['as' => 'user.profile.likes', 'uses' => 'ProfileController@likes']);
Route::get('user/{username}/followers', ['as' => 'user.profile.followers', 'uses' => 'ProfileController@followers']);
Route::get('user/{username}/following', ['as' => 'user.profile.following', 'uses' => 'ProfileController@following']);

// Contact Routes
Route::get('clooud/contact', ['as' => 'contact.show', 'uses' => 'ContactController@show']);
Route::post('clooud/contact', ['as' => 'contact.post.message', 'uses' => 'ContactController@postMessage']);
Route::post('dmca', ['as' => 'dmca.post.message', 'uses' => 'ContactController@postDMCAMessage']);

// Affiliate Route to create a view/download entry
Route::post('affiliate/create/video/{slug}/{embed}', ['as' => 'affiliate.create.video', 'uses' => 'User\AffiliateVideoViewController@create']);
Route::post('affiliate/create/image/{slug}/{embed}', ['as' => 'affiliate.create.picture', 'uses' => 'User\AffiliateImageViewController@create']);
Route::post('affiliate/create/audio/{slug}/{embed}', ['as' => 'affiliate.create.audio', 'uses' => 'User\AffiliateAudioViewController@create']);

// Upload Media Routes
if(! App::runningInConsole() && ! Request::is('install') && ! Request::is('install/*')) {
    $setting = new \App\Setting();
    if($setting->gets('media')->guest_uploads) {
        Route::group(['namespace' => 'User'], function () {
            Route::get('upload', ['as' => 'upload', 'uses' => 'MediaController@index']);
            Route::post('upload', ['as' => 'media.upload', 'uses' => 'MediaController@uploadHandler']);
        });
    } elseif ($setting->gets('media')->editor_uploads) {
        Route::group(['middleware' => ['auth', 'role:admin', 'role:editor'], 'namespace' => 'User'], function () {
            Route::get('upload', ['as' => 'upload', 'uses' => 'MediaController@index']);
            Route::post('upload', ['as' => 'media.upload', 'uses' => 'MediaController@uploadHandler']);
        });
    } else {
        Route::group(['middleware' => 'auth', 'namespace' => 'User'], function () {
            Route::get('upload', ['as' => 'upload', 'uses' => 'MediaController@index']);
            Route::post('upload', ['as' => 'media.upload', 'uses' => 'MediaController@uploadHandler']);
        });
    }
}

// Auth User Routes
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'role:user', 'namespace' => 'User'], function () {

        // Like Route
        Route::put('like', ['as' => 'media.like', 'uses' => 'MediaController@putLike']);

        // Comments Routes
        Route::put('comment', ['as' => 'media.add.comment', 'uses' => 'MediaController@putComment']);
        Route::put('comment/like', ['as' => 'media.comment.like', 'uses' => 'MediaController@putCommentLike']);

        // Follow Controller
        Route::put('follow', ['as' => 'follow', 'uses' => 'FollowController@putFollow']);
        Route::get('follow_feeds', ['as' => 'follow.feeds', 'uses' => 'FollowController@followFeeds']);


        // Media Edit Routes
        Route::get('media/{slug}/edit', ['as' => 'media.edit', 'uses' => 'MediaController@edit']);
        Route::patch('media/{slug}', ['as' => 'media.update', 'uses' => 'MediaController@update']);
        Route::delete('media/{slug}', ['as' => 'media.destroy', 'uses' => 'MediaController@destroy']);

        // Media Approve Route
        Route::get('media/{id}/{approve}', ['as' => 'media.approve', 'uses' => 'MediaController@approve']);

        // File Manager Routes
        Route::resource('manage', 'MediaManageController');

        // Affiliate Routes
        Route::group(['prefix' => 'affiliate'], function () {
            Route::get('/', ['as' => 'affiliate.statistics', 'uses' => 'AffiliateController@statistics']);

            Route::get('/statistics/video', ['as' => 'affiliate.statistics.video', 'uses' => 'AffiliateVideoViewController@statistics']);
            Route::get('/statistics/image', ['as' => 'affiliate.statistics.image', 'uses' => 'AffiliateImageViewController@statistics']);
            Route::get('/statistics/audio', ['as' => 'affiliate.statistics.audio', 'uses' => 'AffiliateAudioViewController@statistics']);

            Route::get('payment', ['as' => 'affiliate.payment', 'uses' => 'AffiliatePayoutController@index']);
            Route::post('payment', ['as' => 'affiliate.pay_me', 'uses' => 'AffiliatePayoutController@payMe']);
            Route::patch('payment', ['as' => 'affiliate.update.email', 'uses' => 'AffiliatePayoutController@updateEmail']);

            Route::get('info', ['as' => 'affiliate.info', 'uses' => 'AffiliateController@info']);

            Route::get('settings', ['as' => 'affiliate.settings', 'uses' => 'AffiliateController@settings']);
            Route::patch('settings/update', ['as' => 'affiliate.settings.update', 'uses' => 'AffiliateController@updateSettings']);
        });

        // profile settings
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', ['as' => 'settings.profile', 'uses' => 'SettingController@profile']);

            Route::patch('profile', ['as' => 'settings.profile.update', 'uses' => 'SettingController@updateProfile']);
            Route::patch('profile/avatar', ['as' => 'settings.avatar.update', 'uses' => 'SettingController@updateAvatar']);

            Route::get('affiliate', ['as' => 'user.settings.affiliate', 'uses' => 'SettingController@editAffiliate']);
            Route::patch('affiliate/update', ['as' => 'user.settings.affiliate.update', 'uses' => 'SettingController@updateAffiliate']);

            Route::get('notifications', ['as' => 'settings.notifications', 'uses' => 'SettingController@editNotifications']);
            Route::patch('notifications/update', ['as' => 'settings.notifications.update', 'uses' => 'SettingController@updateNotifications']);

            Route::get('password', ['as' => 'settings.password.edit', 'uses' => 'SettingController@editPassword']);
            Route::patch('password/update', ['as' => 'settings.password.update', 'uses' => 'SettingController@updatePassword']);
        });
    });

    // Admin Routes

    Route::group(['prefix' => 'admin', 'middleware' => 'role:admin', 'namespace' => 'Admin'], function () {

        // Dashboard Controller
        Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@index']);

        // User Resource
        Route::resource('users', 'UsersController');

        Route::put('users/block/{id}', ['as' => 'users.block', 'uses' => 'UsersController@block']);

        // Media Resource
        Route::resource('medias', 'MediaController');
        Route::put('medias/approve/{id}', ['as' => 'medias.approve', 'uses' => 'MediaController@approve']);

        // Categories Resource
        Route::resource('categories', 'CategoryController');

        // Categories Resource
        Route::resource('forum', 'ForumController');

        // Comments Resource
        Route::resource('comments', 'CommentsController');

        // Pages Resource
        Route::resource('pages', 'PagesController');
        Route::resource('footer-pages', 'FooterPagesController');

        // Appearance Routes
        Route::get('appearance/menu', ['as' => 'admin.appearance.menu', 'uses' => 'AppearanceController@getMenu']);
        Route::put('appearance/sort/{type}', ['as' => 'admin.appearance.menu.sort', 'uses' => 'AppearanceController@putSort']);
        Route::get('appearance/themes', ['as' => 'admin.appearance.themes', 'uses' => 'AppearanceController@getThemes']);
        Route::patch('appearance/themes', ['as' => 'admin.appearance.themes.update', 'uses' => 'AppearanceController@updateTheme']);


        // Settings Routes
        Route::get('settings/media', ['as' => 'settings.media', 'uses' => 'SettingController@media']);
        Route::patch('settings/media', ['as' => 'settings.media.update', 'uses' => 'SettingController@updateMedia']);

        Route::get('settings/points', ['as' => 'settings.points', 'uses' => 'SettingController@points']);
        Route::patch('settings/points', ['as' => 'settings.points.update', 'uses' => 'SettingController@updatePoints']);

        Route::get('settings/comments', ['as' => 'settings.comments', 'uses' => 'SettingController@comments']);
        Route::patch('settings/comments', ['as' => 'settings.comments.update', 'uses' => 'SettingController@updateComments']);

        Route::get('settings/social_login', ['as' => 'settings.social.login', 'uses' => 'SettingController@socialLogin']);
        Route::patch('settings/social_login', ['as' => 'settings.social.login.update', 'uses' => 'SettingController@updateSocialLogin']);

        Route::get('settings/social_links', ['as' => 'settings.social.links', 'uses' => 'SettingController@socialLinks']);
        Route::patch('settings/social_links', ['as' => 'settings.social.links.update', 'uses' => 'SettingController@updateSocialLinks']);

        Route::get('settings/captcha', ['as' => 'settings.captcha', 'uses' => 'SettingController@captcha']);
        Route::patch('settings/captcha', ['as' => 'settings.captcha.update', 'uses' => 'SettingController@updateCaptcha']);

        Route::get('settings/comments', ['as' => 'settings.comments', 'uses' => 'SettingController@comments']);
        Route::patch('settings/comments', ['as' => 'settings.comments.update', 'uses' => 'SettingController@updateComments']);

        Route::get('settings/email', ['as' => 'settings.email', 'uses' => 'SettingController@email']);
        Route::patch('settings/email', ['as' => 'settings.email.update', 'uses' => 'SettingController@updateEmail']);

        Route::get('settings/general', ['as' => 'settings.general', 'uses' => 'SettingController@general']);
        Route::patch('settings/general', ['as' => 'settings.general.update', 'uses' => 'SettingController@updateGeneral']);

        Route::get('settings/analytics', ['as' => 'settings.analytics', 'uses' => 'SettingController@analytics']);
        Route::patch('settings/analytics', ['as' => 'settings.analytics.update', 'uses' => 'SettingController@updateAnalytics']);

        Route::get('settings/advertisements', ['as' => 'settings.advertisements', 'uses' => 'SettingController@advertisements']);
        Route::patch('settings/advertisements', ['as' => 'settings.advertisements.update', 'uses' => 'SettingController@updateAdvertisements']);

        Route::get('settings/cache', ['as' => 'settings.cache', 'uses' => 'SettingController@cache']);
        Route::patch('settings/cache', ['as' => 'settings.cache.update', 'uses' => 'SettingController@updateCache']);

        Route::get('settings/affiliate', ['as' => 'settings.affiliate', 'uses' => 'SettingController@affiliate']);
        Route::patch('settings/affiliate', ['as' => 'settings.affiliate.update', 'uses' => 'SettingController@updateAffiliate']);

        Route::get('settings/tags', ['as' => 'settings.tags', 'uses' => 'SettingController@tags']);
        Route::patch('settings/tags', ['as' => 'settings.tags.update', 'uses' => 'SettingController@updateTags']);

        Route::get('settings/adblock', ['as' => 'settings.adblock', 'uses' => 'SettingController@adblock']);
        Route::patch('settings/adblock', ['as' => 'settings.adblock.update', 'uses' => 'SettingController@updateAdblock']);

        Route::get('settings/storage', ['as' => 'settings.storage', 'uses' => 'SettingController@storage']);
        Route::patch('settings/storage', ['as' => 'settings.storage.update', 'uses' => 'SettingController@updateStorage']);

        // Advanced Routes for Logs
        Route::get('advanced/logs', ['as' => 'advanced.logs', 'uses' => 'LogController@index']);
        Route::get('advanced/logs/preview/{file_name}', ['as' => 'advanced.logs.preview', 'uses' => 'LogController@preview']);
        Route::get('advanced/logs/download/{file_name}', ['as' => 'advanced.logs.download', 'uses' => 'LogController@download']);
        Route::get('advanced/logs/delete/{file_name}', ['as' => 'advanced.logs.delete', 'uses' => 'LogController@delete']);

        // Advanced Routes for Backups
        Route::get('advanced/backups', ['as' => 'advanced.backups', 'uses' => 'BackupController@index']);
        Route::put('advanced/backups', ['as' => 'advanced.backups.create', 'uses' => 'BackupController@create']);
        Route::get('advanced/backups/download', ['as' => 'advanced.backups.download', 'uses' => 'BackupController@download']);
        Route::get('advanced/backups/delete', ['as' => 'advanced.backups.delete', 'uses' => 'BackupController@delete']);

        // Advanced Routes for Recommended Settings
        Route::get('advanced/recommended', ['as' => 'advanced.recommended', 'uses' => 'SettingController@recommended']);

        // Messages Resource
        Route::resource('messages', 'MessageController');

        // Flags Resource
        Route::resource('flags', 'FlagsController');

        // Honest Sports resources
        Route::group(['as' => 'admin.'], function () {
            Route::resource('teams', 'TeamsController');
            Route::resource('sports', 'SportsController');
            Route::resource('types', 'EventTypeController');
            Route::resource('events', 'EventsController');
            Route::resource('archives', 'ArchivesController');
        });
    });
});

// Page Routes - make sure they are placed at the bottom of the routes file
// because {parent_slug}/{slug} would match anything else as well
Route::get('page/{slug}', ['as' => 'page.show', 'uses' => 'PageController@show']);
Route::get('{parent_slug}/{slug}', ['as' => 'page.footer.show', 'uses' => 'PageController@show']);
