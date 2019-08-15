<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Sport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SportsController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'sports');
    }

    public function index()
    {
        $sports = Sport::all();
        return view('admin.sports.index', compact('sports'));
    }

    public function create()
    {
        return view('admin.sports.create');
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'logo' => 'image'
        // ]);
        $sport = new Sport($request->except('logo'));

        if($request->file('logo')){
            $logo = $request->file('logo');
            if(\App::environment('production')){
                $path = Storage::disk('s3')->put('public/uploads/sports', $logo);
            }else{
                $path = Storage::put('public/uploads/sports', $logo);
            }
            $sport->logo = $path;
        }

        $sport->save();

        flash('Sport has been saved!', 'success');
        return redirect()->route('admin.sports.index');
    }

    public function edit(Sport $sport, Request $request)
    {
        return view('admin.sports.edit', compact('sport'));
    }

    public function update(Sport $sport, Request $request)
    {
        if($request->logo){
            if(\App::environment('production')){
                Storage::disk('s3')->delete($sport->logo);
            }else{
                Storage::delete($sport->logo);
            }
            $logo = $request->file('logo');
            if(\App::environment('production')){
                $path = Storage::disk('s3')->put('public/uploads/sports', $logo);
            }else{
                $path = Storage::put('public/uploads/sports', $logo);
            }
            $sport->logo = $path;
            $sport->save();
        }

        $sport->update($request->all());
        flash('The sport was successfully updated!', 'success');
        return redirect()->route('admin.sports.index');
    }

    public function destroy(Sport $sport)
    {
        $sport->delete();
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
