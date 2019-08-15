<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Team;
use App\User;
use App\Sport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class TeamsController extends Controller
{

    public function __construct()
    {
        View::share('menu', 'teams');
    }
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        $sports = Sport::all();
        $users = User::all();

        return view('admin.teams.edit', compact('team', 'sports', 'users'));
    }

    public function update($id, Request $request)
    {
        // dd($request->all());
        $team = Team::findOrFail($id);
        $team->update($request->except('avatar'));

        if($request->file('avatar')){
            if(\App::environment('production')){
                if(Storage::disk('s3')->exists($team->avatar)){
                    Storage::disk('s3')->delete($team->avatar);
                }
            }else{
                if(Storage::exists($team->avatar)){
                    Storage::delete($team->avatar);
                }
            }
            $file = $request->file('avatar');
            if(\App::environment('production')){
                $path = Storage::disk('s3')->putFileAs('public/uploads/avatars', $file, str_random(6) . '.' . $file->extension());
            }else{
                $path = Storage::putFileAs('public/uploads/avatars', $file, str_random(6) . '.' . $file->extension());
            }
            $team->avatar = $path;
            $team->save();
        }

        flash('Team has been updated!', 'success');
        return back();
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
