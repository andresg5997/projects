<?php

namespace App\Http\Controllers\Team;

use App\Team;
use App\Event;
use App\LineUp;
use App\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LineUpController extends Controller
{

    public function index($team_id){
        $team = Team::find($team_id);

        return view('teams.lineups.index', compact('team'));
    }

    public function ajax($team_id){
        // Primero verificamos si existe o no un evento próximo, si no, creamos un nuevo evento con nombre "Sin evento"
        if(!$event = Team::find($team_id)->events()->with('lineups')->whereDate('date', '>=', \Carbon\Carbon::today())->orderBy('date', 'ASC')->first()){
            $event = new Event(['name' => trans('team.no_event')]);
        }
        $event->players = $this->getAvailablePlayers($event); // Conseguimos los jugadores disponibles para el evento

        // Traemos el equipo con todas sus relaciones
        $team = Team::with('sport', 'events', 'events.lineups')->find($team_id);
        // Conseguimos los jugadores disponibles de todos los eventos
        // $events = $team->events->map(function($event){
        //     $event->players = $this->getAvailablePlayers($event);
        //     return $event;
        // });
        return response()
            ->json([
                    'lineups' => $event->lineups,
                    // 'events' => $events,
                    'event'  => $event,
                    'sport' => $team->sport
                ]);
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

    public function store($team_id, Request $request){
        $players = [];
        foreach($request->players as $player){
            if($player['position'] != '0'){
                $players[] = [
                    'position'      => $player['position']['name'],
                    'grid'          => $player['position']['grid'],
                    'mode'          => $player['mode'],
                    'player_id'     => $player['id'],
                    'first_name'    => $player['first_name'],
                    'last_name'     => $player['last_name'],
                    'name'          => $player['name']
                ];
            }
        }
        if (count($players) === 0){
            return response()->json(
                    ['saved' => false]
                , 422);
        }

        // Guardamos el primer lineup
        $lineup = new LineUp($request->all());
        $lineup->user_id = \Auth::id();
        $lineup->line_up = json_encode($players);
        $lineup->save();

        // Luego, hacemos todas las rotaciones que haya solicitado el usuario
        for ($i = 0; $i < $request->quantity-1; $i++) {
            $players = $this->rotatePositions($players, $lineup);
        }

        return response()
            ->json([
                    'saved' => true
                ]);
    }

    public function rotatePositions($players, LineUp $lineup){
        $players = $this->filterPlayers($players); // Array con keys "rest" y "fixed"
        $copy = $players['rest'];
        // Utilizamos el rest para hacer la rotación con el resto de las posiciones
        foreach($players['rest'] as $index => $player){
            $p = array_shift($copy); // Tomamos el primer item en el array
            $copy[] = $p; // Lo ponemos de último
            $players['rest'][$index]['position'] = $copy[0]['position'];
            $players['rest'][$index]['grid'] = $copy[0]['grid'];
        }
        // Combinamos ambos arrays en uno solo
        $players = array_merge($players['fixed'], $players['rest']);
        $this->createLineUp($players, $lineup); // Guardamos el lineup
        return $players;
    }

    public function createLineUp($players, LineUp $lineup){
        $lineup = new LineUp(['event_id' => $lineup->event_id, 'user_id' => $lineup->user_id, 'sport_id' => $lineup->sport_id]);
        $lineup->line_up = json_encode($players);
        $lineup->save();
        return $lineup;
    }

    public function isFixed($player){
        if($player){
            return $player['mode'] == '0' ? false : true;
        }
    }

    public function filterPlayers($players){
        $length = count($players);
        $fixed = [];
        $rest = [];
        foreach($players as $player){
            if($this->isFixed($player)){
                $fixed[] = $player;
            }else{
                $rest[] = $player;
            }
        }
        return ['fixed' => $fixed, 'rest' => $rest];
    }

    public function show($team_id, $event_id){
        $event = Event::has('lineups')->findOrFail($event_id);

        return response()
            ->json([
                    'lineups' => $event->lineups
                ]);
    }

    public function update($team_id, $lineup_id, Request $request){
        $lineup = LineUp::find($lineup_id);
        $positions = json_decode($lineup->line_up, true);

        // Buscamos el índice del jugador dentro de las posiciones del line up
        $index = array_search($request->player['player_id'], array_column($positions, 'player_id'));

        $fullLine = $request->player;
        $fullLine['position'] = $request->position;
        $fullLine['grid'] = $request->grid;
        $replacement = array($index => $fullLine); // Creamos un array para reemplazar el viejo lineup

        $positions = array_replace($positions, $replacement); // Reemplazamos el jugador en su mismo índice con su nueva posición
        $lineup->line_up = json_encode($positions);
        $lineup->save();

        return response()
            ->json([
                    'updated'   => true
                ]);
    }
}
