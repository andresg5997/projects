<?php

namespace App\Http\Controllers\Admin;

use View;
use App\Team;
use App\Event;
use App\Sport;
use App\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    public function __construct()
    {
        View::share('menu', 'events');
    }

    public function index()
    {
        $events = Event::with('teams', 'type', 'sport')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        Event::create($request->all());
        flash('Event has been saved!', 'success');
        return redirect()->route('admin.events.index');
    }

    public function edit(Event $event, Request $request)
    {
        $teams = Team::all();
        $types = EventType::all();
        $sports = Sport::all();
        return view('admin.events.edit', compact('event', 'teams', 'sports', 'types'));
    }

    public function update(Event $event, Request $request)
    {
        $event->update($request->all());
        $event->teams()->detach();
        $event->teams()->attach($request->team_1, ['team_2' => $request->team_2, 'goals_1' => (int)$request->goals_1, 'goals_2' => (int)$request->goals_2]);
        flash('The event was successfully updated!', 'success');
        return redirect()->route('admin.events.index');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()
            ->json([
                'deleted' => true
            ]);
    }
}
