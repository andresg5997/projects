<?php

namespace App\Http\Controllers\Team;

use App\Team;
use Calendar;
use App\Event;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\CalendarLinks\Link;
use App\Http\Controllers\Controller;
use jpmurray\LaravelRrule\Recurrence;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEventRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $team = Team::with('events', 'events.type')->find($id);
        $events = [];

        foreach($team->events as $event){
            // Parseamos el timestamp al formato que requiere FullCalendar
            $dateTime = explode(' ', $event->date);
            $date = $dateTime[0];
            $time = $dateTime[1];
            $time = explode(':', $time);
            $time = $time[0] . $time[1];
            $start = $date . 'T' . $time;

            $type = $event->type->name;
            // Segun el tipo de evento, creamos las variables necesarias
            switch($type){
                case 'Game':
                    $icon = 'fab fa-google';
                    $bgColor = '#f44336';
                break;

                case 'Party':
                    $icon = 'fa fa-birthday-cake';
                    $bgColor = '#039be5';
                break;

                case 'Practice':
                    $icon = 'fab fa-product-hunt';
                    $bgColor = '#8bc34a';
                break;

                default:
                    $icon = 'fas fa-star';
                    $bgColor = '#FFB600';
                break;
            }

            // Si existe fecha de finalización del evento, lo parseamos al formato de FullCalendar
            if($event->end_date){
                $dateTime = explode(' ', $event->end_date);
                $date = $dateTime[0];
                $time = $dateTime[1];
                $time = explode(':', $time);
                $time = $time[0] . $time[1];
                $end = $date . 'T' . $time;
            }else{
                $end = $start;
            }
            // Creamos un calendar event y lo añadimos a un array para luego usarlo en el FullCalendar.

            $events[] = Calendar::event($event->name, false, $start, $end, $event->id, ['color' => $bgColor, 'icon' => $icon]);
        }

        $types = \App\EventType::all();
        $calendar = Calendar::addEvents($events)
                    ->setOptions(['timeFormat' => 'H:mm a'])
                    ->setCallbacks(['dayClick' =>
                                '
                                    function(date, jsEvent, view){
                                        app.dateSetter(date.format())
                                    }
                                ',
                                'eventClick' => '
                                    function (calEvent, jsEvent, view){
                                        app.showEvent(calEvent.id)
                                    }
                                ',
                                'eventRender' => '
                                    function(event, element){
                                        if(event.icon){
                                            element.find(".fc-time").prepend("<i class=\'"+event.icon+"\' style=\'margin-right: 5px\'></i>");
                                        }
                                    }
                                '
                            ]);

        return view('teams.schedule.index', compact('calendar', 'team', 'types'));
    }

    public function store(Request $request, $id)
    {
        // http_response_code(500);

        // dd($request);
        $validator = Validator::make($request->all(), [
            'name'          => 'required|min:4',
            'date'          => 'date|after:yesterday',
            'endDate'       => 'required',
            'time'          => 'required',
            'endTime'       => 'required|after:time',
            'enemy_team'        => 'required_if:type_id,1',
            'location_name' => 'required'
        ]);

        $errorData = [];

        foreach($validator->errors()->messages() as $field => $error){
            $errorData[$field] = true;
        }

        if ($validator->fails()) {
            return response(json_encode($errorData), 422);
        }

        $team = Team::find($id);

        if ($team->owner->id != \Auth::id()){
            return response('You can\'t create events for a team that\'s not yours.', 403);
        }

        return response()
            ->json([
                    'saved' => true
                ]);
    }

    public function postStore(Request $request, $id)
    {
        $team = Team::find($id);
        $time = date('H:i', strtotime($request->time));
        $endTime = date('H:i', strtotime($request->endTime));

        $date = date('y-m-d', strtotime($request->date));
        $endDate = date('y-m-d', strtotime($request->endDate));
        if($request->frequency != ''){ // Si su frecuencia no está vacía
            $c = Carbon::parse($request->repeatDate);
            $recurrence = new Recurrence;
            $recurrence->setStart(Carbon::parse($request->date));
            $recurrence->setUntil($c);
            $recurrence->setInterval($request->interval);

            $time = date('H:i', strtotime($request->time));
            $endTime = date('H:i', strtotime($request->endTime));

            $request->date = $date = date('y-m-d', strtotime($request->date));
            $request->endDate = $endDate = date('y-m-d', strtotime($request->endDate));

            switch($request->frequency){
                // case 'times':
                //     $recurrence->setCount($request->times);
                //     $this->handleRecurrence($recurrence, $request);
                // break;
                case 'week':
                    $recurrence->setFrequency('weekly');
                    // dd($request->days);
                    $days = [];
                    foreach($request->days as $day){
                        $days[] = [$day, null];
                    }

                    $recurrence->setDays($days);

                    $this->handleRecurrence($recurrence, $request);
                break;

                case 'month':
                // dd('Month received');
                    $recurrence->setFrequency('monthly');

                    if($request->month['type'] == '0'){
                        $recurrence->setDays([
                                [$request->month['positionMonth'], $request->month['position']]
                            ]);
                    }

                    $this->handleRecurrence($recurrence, $request);

                break;

                case 'year':
                    $recurrence->setFrequency('yearly');

                    if(count($occurences) == 0){
                        return response('Please check if you filled all the fields correctly', 400);
                    }
                    $this->handleRecurrence($recurrence, $request);
                break;

                default:
                break;
            }
        }else{

            $event = new Event($request->all());
            $event->date = $date . ' ' . $time;
            $event->end_date = $endDate . ' ' . $endTime;
            $event->user_id = \Auth::id();
            $event->sport_id = $team->sport_id;
            $event->save();
            // $event->teams()->attach($request->team_id, ['team_2' => $request->team_2]);
        }
        return redirect()->route('schedule.index', $id);
    }

    public function handleRecurrence($recurrence, Request $request){
        $team = Team::find($request->team_id);
        $recurrence->build();
        // Se obtienen todas las ocurrencias del juego por la recurrencia solicitada
        $occurences = $recurrence->getOccurences();

        // Creamos un evento por cada ocurrencia
        foreach($occurences as $occurence){
            $event = new Event($request->all());
            $event->date = $occurence->start->format('Y-m-d') . ' ' . $request->time . ':00';
            $event->end_date = $occurence->start->format('Y-m-d') . ' ' . $request->endTime . ':00';
            $event->user_id = \Auth::id();
            $event->sport_id = $team->sport_id;
            $event->save();

            $event->teams()->attach($request->team_id, ['team_2' => $request->team_2]);
        }
    }

    public function getTeams($id){
        $teams = Team::where('id', '<>' , $id)->get();

        return response()
            ->json([
                    'teams' => $teams
                ]);
    }

    public function updateEvent(Request $request){
        http_response_code(500);
        dd($request->all());
    }

    public function exportEventTo($calendar, $event_id)
    {
        $event = Event::with('type')->find($event_id);
        $from = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime($event->date)));
        $to = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i', strtotime($event->end_date)));

        $link = Link::create($event->name, $from, $to)
            ->description($event->type->name)
            ->address($event->location_name);

        switch($calendar) {
            case 'google':
            $url = $link->google();
            break;
            case 'ical':
            $content = $link->ics();
            $path = 'public/calendars/' . str_random() . '.ics';
            if(\App::environment('production')){
                Storage::disk('s3')->put($path, $content);
                $file = Storage::disk('s3')->url($path);
            }else{
                Storage::put($path, $content);
                $file = Storage::url($path);
            }
            return response()->download(public_path($file));
            break;
            case 'yahoo':
            $url = $link->yahoo();
            break;
        }
        // dd($url);
        return redirect($url);
    }

    public function postUpdate(Request $request, $id)
    {
        $team = Team::find($id);
        $time = date('H:i', strtotime($request->time));
        $endTime = date('H:i', strtotime($request->endTime));

        $date = date('y-m-d', strtotime($request->date));
        $endDate = date('y-m-d', strtotime($request->endDate));
        if($request->frequency != ''){ // Si su frecuencia no está vacía
            $c = Carbon::parse($request->repeatDate);
            $recurrence = new Recurrence;
            $recurrence->setStart(Carbon::parse($request->date));
            $recurrence->setUntil($c);
            $recurrence->setInterval($request->interval);

            $time = date('H:i', strtotime($request->time));
            $endTime = date('H:i', strtotime($request->endTime));

            $date = date('y-m-d', strtotime($request->date));
            $endDate = date('y-m-d', strtotime($request->endDate));

            switch($request->frequency){
                // case 'times':
                //     $recurrence->setCount($request->times);
                //     $this->handleRecurrence($recurrence, $request);
                // break;
                case 'week':
                    $recurrence->setFrequency('weekly');
                    // dd($request->days);
                    $days = [];
                    foreach($request->days as $day){
                        $days[] = [$day, null];
                    }

                    $recurrence->setDays($days);

                    $this->handleRecurrence($recurrence, $request);
                break;

                case 'month':
                // dd('Month received');
                    $recurrence->setFrequency('monthly');

                    if($request->month['type'] == '0'){
                        $recurrence->setDays([
                                [$request->month['positionMonth'], $request->month['position']]
                            ]);
                    }

                    $this->handleRecurrence($recurrence, $request);

                break;

                case 'year':
                    $recurrence->setFrequency('yearly');

                    if(count($occurences) == 0){
                        return response('Please check if you filled all the fields correctly', 400);
                    }
                    $this->handleRecurrence($recurrence, $request);
                break;

                default:
                break;
            }
        }else{

            $event = new Event($request->all());
            $event->date = $date . ' ' . $time;
            $event->end_date = $endDate . ' ' . $endTime;
            $event->user_id = \Auth::id();
            $event->sport_id = $team->sport_id;
            $event->save();
            $event->teams()->attach($request->team_id, ['team_2' => $request->team_2]);
        }
        return redirect()->route('schedule.index', $id);
    }
}
