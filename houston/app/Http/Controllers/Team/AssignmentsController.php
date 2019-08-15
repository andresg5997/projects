<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Player;
use App\Assignment;
use App\Event;
use App\Traits\CheckEvent;

class AssignmentsController extends Controller
{
    use CheckEvent;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $team = Team::find($id);
        if(!$team->isOwner(\Auth::id())){
            return view('errors.404');
        }
        $events = Team::with('events', 'events.assignments')->find($id)->events;

        return view('teams.assignments.index', compact('team'));
    }

    public function getAvailablePlayers(Event $event){
        $players = $event->availability()->with('player')->where('status', '>', 1)->select()->get()
        ->map(function($availability){
            if($player = $availability->player) {
                $player->status = $availability->status;
                $player->position = '0';
                $player->mode = '0';
                $player->grid = '0';
                $player->name = $player->first_name . ' ' . $player->last_name;
                return $player;
            }
        });
            if(count($players) > 0){
            return $players;
        }
        return [];
    }

    public function ajax($team_id, $event_id = null){
        if(!$event_id){
            $event = $this->checkEvent($team_id); // Busca el próximo evento del equipo
        }else{
            $event = Event::with('assignments')->find($event_id);
        }
        $event->players = $this->getAvailablePlayers($event);

        $team = Team::with('events')->find($team_id);
        // Me traigo todos los eventos que hayan de hoy en adelante.
        $events = $team->events()->with('assignments')->whereHas('type', function ($query) {
            $query->where('name', '<>', 'Practice');
            // $query->where('name', '=', 'Party');
            // $query->where('name', '=', 'Game');
        })->whereDate('date', '>=', date('Y-m-d'))->take(8)->get();

        return response()
            ->json([
                    'assignments' => $event->assignments,
                    'event'       => $event,
                    'events'      => $events
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Verifico si ya existe una asignación para el jugador, si es verdadero, lo actualizo, si no, la creo.
        if($assignment = Assignment::where('player_id', $request->pk)->where('event_id', $request->name)->first()){
            $assignment->details = $request->value;
            $assignment->save();
            return response()->json(['saved' => true]);
        }
        $assignment = Assignment::create(['player_id' => $request->pk, 'event_id' => $request->name, 'details' => $request->value]);
        return response()->json(['saved' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($team_id)
    {
        $team = Team::find($team_id);
        $events = Team::with('events', 'events.assignments')->find($team_id)->events;

        return view('teams.assignments.index', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $team_id, $id)
    {
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
