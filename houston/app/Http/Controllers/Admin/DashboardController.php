<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Flag;
use App\Page;
use App\Team;
use App\User;
use App\Media;
use App\Sport;
use App\Archive;
use App\Comment;
use App\Setting;
use App\Category;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        View::share('menu', 'dashboard');
    }

    public function index()
    {
        $version = '1.2.1 - GitHub';

        $users = User::count();
        $categories = Category::count();
        $media = Media::count();
        $comments = Comment::count();
        $pages = Page::count();
        $flags = Flag::count();
        $teams = Team::count();
        $sports = Sport::count();
        $archives = Archive::count();

        $ffmpeg = command_exist('ffmpeg --help');
        $handbrake = command_exist('HandBrakeCLI --help');
        $youtube_dl = command_exist('youtube-dl --help');
        $gdrive = command_exist('gdrive help');

        $shell_exec = isEnabled('shell_exec');
        $exec = isEnabled('exec');

        $attributes = json_decode(Setting::where('name', 'media')->value('attributes'));

        return view('admin.dashboard', compact('version', 'users', 'categories', 'media', 'comments', 'pages', 'flags', 'ffmpeg', 'handbrake', 'youtube_dl', 'gdrive', 'attributes', 'shell_exec', 'exec', 'teams', 'sports', 'archives'));
    }
}
