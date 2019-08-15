<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Archive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArchivesController extends Controller
{

    public function __construct()
    {
        View::share('menu', 'archives');
    }

    public function index()
    {
        $archives = Archive::all();
        return view('admin.archives.index', compact('archives'));
    }

    public function edit(Archive $archive)
    {
        return view('admin.archives.edit', compact('archive'));
    }

    public function update(Archive $archive, Request $request)
    {
        $archive->update($request->all());
        flash('The archive was updated!', 'success');
        return redirect()->route('admin.archives.index');
    }

    public function destroy(Archive $archive)
    {
        $archive->delete();
        flash('The archive was deleted!', 'success');
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
