<?php
namespace App\Http\Controllers\Team;

use Excel;
use Storage;
use App\Team;
use App\Events\PlayerRegistered;
use App\Player;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RosterController extends Controller
{

    public $team;

    public function index($id){
    	$players = Player::all();
        $team = Team::find($id);
    	return view('teams.roster.index', compact('players', 'team'));
    }

    public function ajax($id){
        $team = Team::with('players')->where('id', $id)->first();
        // dd($team->players);
        return (string)$team->players;
    }

    public function create($id){
        $team = Team::find($id);
    	return view('teams.roster.create', compact('team'));
    }

    public function store($id, Request $request){
        $validator = $this->playerValidator($request);

        if ($validator->fails()) {
            $errorData = [];
            foreach($validator->errors()->messages() as $field => $error){
                $errorData[$field] = true;
            }
            return response(json_encode($errorData), 422);
        }

        $player = new Player($request->except(''));

        if($request->picture){ // Verifico que no venga una string vacía
            $base64 = $request->picture;
            $parts = explode(',', $request->picture);
            $decoded = base64_decode($parts[1]);
            $extension = $this->getExtension($base64);
            $fileName = str_random(8) . '.' . $extension;
            if(\App::environment('production')){
                Storage::disk('s3')->put('public/pictures/' . $fileName, $decoded);
            }else{
                Storage::put('public/pictures/' . $fileName, $decoded);
            }
            $player->picture = $fileName;
        }
        $player->save();

        // event(new PlayerRegistered($player));

        return response('', 200);
    }

    public function getExtension($base64)
    {
        $exploded = explode(',', $base64);
        if(str_contains($exploded[0], '/')){
            $extension = explode(';', explode('/', explode(',', $base64)[0])[1])[0];
            return $extension;
        }
        return false;
    }

    public function update(Request $request, $teamId, $playerId){
        $player = Player::find($playerId);
        $validator = $this->playerValidator($request);

        if ($validator->fails()) {
            $errorData = [];
            foreach($validator->errors()->messages() as $field => $error){
                $errorData[$field] = true;
            }
            return response(json_encode($errorData), 422);
        }

        if(strlen($request->picture) > 15){ // Que la imagen sea mayor al largo común de un nombre de imagen (13~14 caracteres)
            if(\App::environment('production')){
                Storage::disk('s3')->delete(['public/pictures/' . $player->picture]);
            }else{
                Storage::delete(['public/pictures/' . $player->picture]);
            }
            $base64 = $request->picture;
            $parts = explode(',', $request->picture);
            $decoded = base64_decode($parts[1]);
            $extension = $this->getExtension($base64);
            $fileName = str_random(8) . '.' . $extension;
            Storage::put('public/pictures/' . $fileName, $decoded);
            $player->picture = $fileName;
        }
        $player->save();

        $player->update($request->except('id', 'picture', 'created_at', 'updated_at'));
        return response()
            ->json([
                'updated' => true
            ]);
    }

    public function show($team_id, $player_id){
        $team = Team::with('players')->find($team_id);
        $player = Player::find($player_id);
        return view('teams.roster.show', compact('team', 'player'));
    }

    public function destroy($team_id, $player_id){
        $player = Player::find($player_id);
        $player->delete();

        return response('Successfully deleted.', 200);
    }

    public function importRoster($id, Request $request){
        $this->team = Team::find($id);

        // Validamos si recibimos el archivo "file"
        if(!$file = $request->file('file')){
            flash()->error(trans('player.import_error'))->important();
            return redirect()->route('roster.index', $this->team->id);
        }
        // Validamos la extensión del archivo
        switch($file->extension()){
            case 'xls':
            case 'xlsx':
            case 'csv':
            // case 'zip':
                $folder = 'public/uploads/xls';
                $filename = str_random() . '.' . $file->extension();
                // dd($path);
                Storage::disk('local')->putFileAs($folder, $file, $filename);
                if(\App::environment('production')) {
                    $folder = 'public/storage/uploads/xls';
                    // $path = '';
                }
                $path = "$folder/$filename";
                $this->fillPlayers($path);
                flash()->success(trans('player.import_success'))->important();
                return redirect()->route('roster.index', $this->team->id);
            break;
            default:
                flash('error', 'Please check if your file extension is XLS, XLSX or CSV.');
                return redirect()->route('roster.index', $this->team->id);
            break;
        }
    }

    public function fillPlayers($filepath){
        if(\App::environment('production')){
            $path = $filepath;
        }else{
            $path = storage_path('app/'. $filepath);
        }
        $rows = Excel::load($path, function($reader){
        })->get()->toArray();
        $players = array();
        foreach($rows as $row){
            if($row['parent']){
                $player = new Player($row);
                $player->team_id = $this->team->id;
                $player->first_name = $row['first'];
                $player->last_name = $row['last'];
                $player->parent_name = $row['parent'];
                // $player->number = $row['number'];
                $player->save();
                // event(new PlayerRegistered($player));
            }
        }
    }

    public function downloadTemplate(){
        return response()->download(public_path('honest_sports_template.xls'));
    }

    public function playerValidator(Request $request){
        $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'parent_name' => 'required',
                'email' => 'required',
                'phone' => 'required'
            ]);

        return $validator;
    }

    public function updatePicture(Request $request)
    {
        $player = Player::find($request->player_id);

        if($player->picture){
            if(\App::environment('production')){
                Storage::disk('s3')->delete(['public/pictures/' . $player->picture]);
            }else{
                Storage::delete(['public/pictures/' . $player->picture]);
            }
        }

        $base64 = $request->picture;
        $parts = explode(',', $request->picture);
        $decoded = base64_decode($parts[1]);
        $extension = $this->getExtension($base64);
        $fileName = str_random(8) . '.' . $extension;
        if(\App::environment('production')){
            Storage::put('public/pictures/' . $fileName, $decoded);
        }else{
            Storage::disk('s3')->put('public/pictures/' . $fileName, $decoded);
        }
        $player->picture = $fileName;
        $player->save();

        return response()
            ->json([
                'saved' => true
            ]);
    }
}
