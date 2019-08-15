<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Team;
use App\EventType;
use App\Sport;
use App\Location;
use App\Event;
use Calendar;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index(){
        $models = Event::select('id', 'name', 'date')->where('user_id', \Auth::id())->get();
        $events = [];

        // Para poder imprimir adecuadamente los eventos en el FullCalendar,
        // se debe hacer una pequeña transformación del dato de fecha y
        // añadir a un array para posteriormente enviar al FullCalendar con su Calendar::event.
        foreach($models as $event){
            $dateTime = explode(' ', $event->date);
            $date = $dateTime[0];
            $time = $dateTime[1];
            $time = explode(':', $time);
            $time = $time[0] . $time[1];
            $start = $date . 'T' . $time;
            $events[] = Calendar::event($event->name, false, $start, $start, $event->id, ['url' => route('schedule.show', $event->id)]);
        }
        $calendar = Calendar::addEvents($events)->setOptions(['firstDay' => 1]);

    	return view('schedule.index', compact('calendar'));
    }

    public function create(){
    	$teams = Team::where('user_id', \Auth::id())->get();
    	$types = EventType::all();
    	$sports = Sport::all();
    	$locations = Location::all();

    	return view('events.create', compact('teams', 'sports', 'types', 'locations'));
    }

    public function store(Request $request){
        $team = Team::find($request->team_1);
    	// dd($request->except(''));
        $event = new Event($request->except(''));
        $event->user_id = \Auth::id();
        // Parseamos la fecha recibida a timestamp para la DB
        $dateTime = explode(' ', $request->date);
        $date = explode('/', $dateTime[0]);
        $date = "$date[2]-$date[0]-$date[1]";
        $time = $dateTime[1];
        $event->date =  $date . ' ' . $time . ':00';
        $event->sport_id = $team->sport_id;
        $event->save();
        flash(trans('event.save_success'))->important();
        return redirect()->route('schedule.index');
    }

    public function show($id){
        // return 'Hello world';
    }

    public function updateEvent(Request $request){
        // return response('', 200);
        $event = Event::find($request->pk);
        $validator = \Validator::make($request->all(), [
                'value.goals_1'         => 'required',
                'value.goals_2'         => 'required'
            ]);

        if($validator->fails()){
            return response('You must fill both fields');
        }

        if($event->user_id === \Auth::id()){ // Si el usuario logeado es el dueño del evento, se actualiza.
            $event->update(['goals_1' => $request->value['goals_1'], 'goals_2' => $request->value['goals_2']]);
            return response()
                ->json([
                        'saved' => true
                    ]);
        }
        return response('Not authorized.', 422);
    }
}
