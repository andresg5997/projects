<?php

namespace App\Traits;
use App\Event;
use App\Team;

trait CheckEvent {
    public function checkEvent($team_id){
        if(!$event = Team::find($team_id)->events()->with('availability')->whereDate('date', '>=', \Carbon\Carbon::today())->orderBy('date', 'ASC')->first()){
            $event = new Event(['name' => trans('team.no_event'), 'id' => 0]);
            $event->availability = [];
            $event->assignments = [];
            $event->lineups = [];
        }
        return $event;
    }
}
