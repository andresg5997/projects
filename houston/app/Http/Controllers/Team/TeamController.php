<?php

namespace App\Http\Controllers\Team;

use Auth;
use App\Game;
use App\Post;
use App\Team;
use App\User;
use App\Media;
use App\Sport;
use App\Archive;
use App\Invitation;
use App\Jobs\InviteCoach;
use App\Events\TeamCreated;
use Illuminate\Http\Request;
use App\Events\TeamCoachSaved;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index(){
        $teams = \Auth::user()->teams;
        $sports = Sport::all();

        return view('teams.index', compact('teams', 'sports'));
    }

    public function create(){
    	$sports = Sport::all();
    	return view('teams.create', compact('sports'));
    }

    public function store(Request $request){
        $this->validate($request, [
                'name' => 'min:4|required',
                // 'city' => 'min:4|required',
                'country' => 'required',
                'founded_at' => 'date|required',
                'sport_id' => 'exists:sports,id',
                // 'avatar' => 'required'
            ]);

        $team = new Team($request->except('avatar'));
        if($request->avatar){
            $parts = explode(',', $request->avatar); // index 0: details, 1: base64 image

            if(str_contains($parts[0], 'jpeg')){
                $extension = '.jpg';
            }else{
                $extension = '.png';
            }

            $path = 'public/uploads/avatars/' . str_random(15) . $extension;
            if(\App::environment('production')){
                Storage::disk('s3')->put($path, base64_decode($parts[1]));
            }else{
                Storage::put($path, base64_decode($parts[1]));
            }
            $team->avatar = $path;
        }

        $team->save();
        \Auth::user()->teams()->attach($team->id, ['owner' => '1']);
        event(new TeamCreated($team));
        return response()
            ->json([
                    'saved' => true
                ]);
    }

    public function show($id){
        session()->put('upload_session', time().'_'.rand(00000000, 99999999));
        $team = Team::where('id', $id)->with('coaches')->first();
        $archives = Archive::where('team_id', $id)->latest()->paginate(5);
        $sliderImages = $this->getSliderImages($id);

        // Variables for the tabs
        $events = $team->events()->whereDate('date', '>', date('m-d-Y'))->orderBy('date', 'ASC')->get();
        $members = $team->users;

        return view('teams.dashboard', compact('team', 'archives', 'sliderImages', 'events', 'members'));
    }

    public function getSliderImages($id){
        $likedPosts = Post::where('team_id', $id)->has('media')->with('media', 'likes')->latest()->limit(3)->get();
        $sliderImages = collect();

        foreach($likedPosts as $post){
            // $post->media_count = 0;

            foreach($post->media as $media){
                $sliderImages->push([
                        'link'      => route('media.show', $media->slug),
                        'slug'      => $media->slug,
                        'url'       => $media->previewImageUrl('original'),
                        'subject'   => $post->subject
                    ]);
            }
        }
        if(count($sliderImages) > 2){
            return $sliderImages;
        }
        $sliderImages = collect();
        $sliderImages->push([
                        'url'       => '/storage/uploads/fills/soccer.jpg'
                    ]);
        for($i = 1; $i < 3; $i++){
            $sliderImages->push([
                    'url'       => '/storage/uploads/fills/soccer' . $i . '.jpg'
                ]);
        }
        // dd($sliderImages);
        return $sliderImages;
    }

    public function dashboardEvents($id){
        $team = Team::with('events')->find($id);
        if(count($team->events) > 0){
            if($team->events()->select('season')->where('type_id', 1)->latest()->first()){
                $season = $team->events()->select('season')->where('type_id', 1)->latest()->first()->season;
            } else {
                $season = false;
            }
        }else{
            $season = false;
        }

        if($season){
            $events = $team->events()->where('type_id', 1)->where('season', $season)->orderBy('date', 'ASC')->get();
        } else {
            $events = $team->events;
        }

        return response()
            ->json([
                    'events' => $events,
                    'season' => $season
                ]);
    }

    public function getEvents($id){
        $team = Team::with('events')->find($id);
        return response()
            ->json([
                'events' => $team->events
            ]);
    }

    public function edit($id){
        $team = Team::find($id);
        $sports = Sport::all();
        return view('teams.edit', compact('team', 'sports'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'name' => 'min:4|required',
                // 'city' => 'min:4|required',
                'country' => 'required',
                'founded_at' => 'date|required',
                'sport_id' => 'exists:sports,id'
            ]);
        $team = Team::find($id);

        $team->update($request->except('avatar'));

        if($request->image){
            $parts = explode(',', $request->image); // index 0: details, 1: base64 image

            if(str_contains($parts[0], 'jpeg')){
                $extension = '.jpg';
            }else{
                $extension = '.png';
            }

            $path = 'public/uploads/avatars/' . str_random(15) . $extension;
            if(\App::environment('production')){
                Storage::disk('s3')->put($path, base64_decode($parts[1]));
            }else{
                Storage::put($path, base64_decode($parts[1]));
            }
            $team->avatar = $path;
        }
        $team->save();

        return response()
            ->json([
                'saved' => true
            ]);
    }

    public function uploadPicture(Request $request, $id){
        $avatar = $request->file('avatar');
        $team = Team::find($id);

        switch($avatar->extension()){
            case 'png':
                if(\App::environment('production')){
                    $path = Storage::disk('s3')->putFileAs('public/uploads/avatars', $avatar, 'team_' . $id . '.png');
                }else{
                    $path = Storage::putFileAs('public/uploads/avatars', $avatar, 'team_' . $id . '.png');
                }
                flash()->success(trans('team.avatar_success'))->important();
                $team->avatar = $path;
                $team->save();
                return redirect()->route('teams.show', $id);
            break;

            case 'jpg':
            case 'jpeg':
            case 'JPG':
                if(\App::environment('production')){
                    $path = Storage::putFileAs('public/uploads/avatars', $avatar, 'team_' . $id . '.jpg');
                }else{
                    $path = Storage::disk('s3')->putFileAs('public/uploads/avatars', $avatar, 'team_' . $id . '.jpg');
                }
                flash()->success(trans('team.avatar_success'))->important();
                $team->avatar = $path;
                $team->save();
                return redirect()->route('teams.show', $id);
            break;

            default:
                flash()->error(trans('team.avatar_error'))->important();
                return redirect()->route('teams.show', $id);
            break;
        }

    }

    public function uploadArchives(Request $request, $id){
        // dd($request->file('archive'));
        $file = $request->file('archive');
        // dd($request->);
        $team = Team::find($id);
        switch($file->extension()){
            case 'doc':
            case 'docx':
            case 'pdf':
            case 'xls':
            case 'xlsx':
            case 'png':
            case 'jpeg':
            case 'jpg':

                $filename = str_random(6) . '.' . $file->extension();
                if(\App::environment('production')){
                    $path = Storage::putFileAs('public/uploads/files', $file, $filename);
                }else{
                    $path = Storage::disk('s3')->putFileAs('public/uploads/files', $file, $filename);
                }
                flash()->success(trans('team.avatar_success'))->important();

                $archive = new Archive;
                $archive->name = $file->getClientOriginalName();
                $archive->team_id = $id;
                $archive->path = $path;
                $archive->user_id = Auth::id();
                $archive->extension = $file->extension();
                $archive->save();
                flash()->success(trans('team.file_success'))->important();
            break;

            default:
                flash()->error(trans('team.file_error'))->important();
                return redirect()->route('teams.show', $id);
            break;
        }

        return redirect()->route('teams.show', $id);
    }

    public function deleteArchive(Archive $archive)
    {
        if(Auth::user()->type == 'admin' || $archive->user->id == Auth::id()){
            $archive->delete();
            return response()
                ->json([
                    'deleted' => true
                ]);
        }
        return response()
            ->json([
                'deleted' => false
            ]);
    }

    public function getTeam($id)
    {
        $team = Team::find($id);

        return response()
            ->json([
                'team' => $team
            ]);
    }

    public function destroy($id)
    {
        Team::find($id)->delete();
        return response()
            ->json([
                'deleted' => true
            ]);
    }

    public static function getMediaPosts($id){
        $posts = Post::where('team_id', $id)->has('media')->with('media', 'user', 'comments', 'comments.user')->latest()->get();

        // Como estamos mostrando los mensajes con Vue, tenemos que utilizar
        // la función getAvatarUrl creada por Clooud para conseguir el avatar
        // del usuario y adherirsela al post.
        $posts->map(function($post){
            $post->avatar_url = getAvatarUrl($post->user->id);
            $post->profile_url = route('user.profile.index', $post->user->username);

            if($post->likeCount > 0){
                $post->likesTotal = $post->likeCount;
            }else{
                $post->likesTotal = 0;
            }

            if(count($post->comments) > 0){
                $post->commentsTotal = count($post->comments);

                // Igual que arriba, conseguimos desde Laravel las url del avatar
                // y el perfil del usuario para utilizarlos en Vue.
                $post->comments->map(function($comment){
                    $comment->avatar_url = getAvatarUrl($comment->user->id);
                    $comment->profile_url = route('user.profile.index', $comment->user->username);
                });
            }else{
                $post->commentsTotal = 0;
            }

            if($post->liked()){
                $post->liked = true;
            }else{
                $post->liked = false;
            }

            // Verificamos si el post tiene media para añadirlos en
            // un formato que podemos recibir y mostrar con Vue.
            if($post->media){
                $post->medias = collect();
                $post->media_count = 0;

                foreach($post->media as $media){
                    $post->media_count++;
                    $post->medias->push([
                            'link'      => route('media.show', $media->slug),
                            'slug'      => $media->slug,
                            'url'       => $media->previewImageUrl('original')
                        ]);
                }
            }


            if(count($post->comments) > 0){
                $post->comments->map(function ($comment){
                    $comment->profile_url = route('user.profile.index', $comment->user->username);
                });
            }

            return $post;
        });
        return response()
            ->json([
                'posts' => $posts
            ]);
    }
}
