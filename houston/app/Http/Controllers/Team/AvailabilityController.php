<?php

namespace App\Http\Controllers\Team;

use App\Team;
use App\Event;
use App\Player;
use App\Availability;
use App\Traits\CheckEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvailabilityController extends Controller
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
        return view('teams.availability.index', compact('team'));
    }

    public function ajax($id){
        $event = $this->checkEvent($id); // Busca el evento más próximo del equipo

        $team = Team::with('events')->find($id);
        $events = $team->events()->with('availability')->whereDate('date', '>=', date('Y-m-d'))->orderBy('date')->take(8)->get();

        return response()
            ->json([
                    'availability' => $event->availability,
                    'events' => $events,
                    'event'  => $event
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($team_id, Request $request)
    {
        $event = Event::find($request->event_id);
        $team = Team::find($team_id);
        $player = Player::find($request->player_id);

        $isOwner = $team->isOwner(\Auth::id());
        $isParent = ($player->email != \Auth::user()->email) ? true : false;
        // Si el usuario logeado no es el padre dle jugador o es el due;o del equipo, no puede cambiar la disponibilidad.
        // http_response_code(500);
        // dd($isOwner);
        if(!$isOwner || !$isParent){
            return response()
                ->json([
                        'saved' => false
                    ], 422);
        }

        // Verifico si ya existe la disponibilidad del usuario y la actualizo si es verdadero
        // si no, la guardo.
        if($av = Availability::where('event_id', $request->event_id)->where('player_id', $request->player_id)->first()){
            $av->status = $request->status;
            $av->save();
        }else{
            $av = new Availability($request->all());
            $av->save();
        }

        return response()
            ->json([
                    'saved' => true,
                    'id'    => $av->id
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update($team_id, $player_id, Request $request)
    {
        //
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
