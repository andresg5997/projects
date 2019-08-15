<?php

namespace App\Http\Controllers\Admin;

use View;
use App\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventTypeController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'types');
    }

    public function index()
    {
        $types = EventType::all();
        return view('admin.types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.types.create');
    }

    public function store(Request $request)
    {
        EventType::create($request->all());
        flash('Event type has been saved!', 'success');
        return redirect()->route('admin.types.index');
    }

    public function edit(EventType $type)
    {
        return view('admin.types.edit', compact('type'));
    }

    public function update(EventType $type, Request $request)
    {
        $type->update($request->all());
        flash('Event type has been updated!', 'success');
        return back();
    }

    public function destroy(EventType $type)
    {
        $type->delete();
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
