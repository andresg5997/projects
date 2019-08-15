<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use App\Invitation;
use Illuminate\Http\Request;
use App\Jobs\SendTeamInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function invite($team_id, Request $request)
    {
        $this->validate($request, [
            'email' => 'email'
        ]);

        // Si el usuario está registrado, le añado el equipo.

        $user = User::where('email', $request->email)->first();
        if($user) {
            $user->teams()->attach($team_id);
            return response()
                ->json([
                    'sent' => true
                ]);
        }
        // Si el usuario no está registrado, lo invito a la plataforma y automáticamente le agrego el equipo.
        else {
            $inv = new Invitation($request->all() + ['team_id' => $team_id, 'user_id' => Auth::id()]);
            $inv->save();

            $team = Team::find($team_id);
            dispatch(new SendTeamInvitation($request->all()), $team);

            return response()
                ->json([
                    'sent' => true
                ]);
        }
        // Al registrarse un nuevo usuario, debo verificar si existen invitaciones pendientes para él.
    }
}
