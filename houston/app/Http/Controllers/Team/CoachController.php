<?php

namespace App\Http\Controllers\Team;

use App\Coach;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachController extends Controller
{

    public function index($id)
    {
        dd($id);
    }

    public function store(Request $request, $team_id)
    {
        $this->validate($request, [
            'name'  => 'required',
            'email'  => 'required|email',
            'title'  => 'required'
        ]);

        Coach::create($request->all() + ['team_id' => $team_id]);

        return response()
            ->json([
                'saved' => true
            ]);
    }

    public function destroy($team_id, $id)
    {
        Coach::find($id)->delete();

        return response()
            ->json([
                'deleted' => true
            ]);
    }

    public function update(Request $request, $team_id, $id)
    {
        Coach::find($id)->update($request->all());
        return response()
            ->json([
                'updated' => true
            ]);
    }
}
