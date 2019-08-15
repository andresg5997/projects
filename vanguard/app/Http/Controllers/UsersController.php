<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
	public function index(){
		return view('admin.usuarios.index');
	}

	public function get($user_id){
		$usuarios = User::whereNotIn('id', [$user_id])->get();

		return response()
			->json([
				'usuarios' => $usuarios
			]);
	}

	public function getAll()
	{
		$usuarios = User::all();
		return response()
			->json([
				'usuarios'  => $usuarios
			]);
	}

	public function show($user_id){
		return view('admin.usuarios.show', compact('user_id'));
	}

	public function update($user_id, Request $request)
	{
		$usuario = User::findOrFail($user_id);

		$this->validate($request, [
			'nombre'        => 'min:3|max:255',
			'apellido'      => 'max:255',
			'email'         => [
				'email',
				'max:255',
				Rule::unique('users')->ignore($usuario->email, 'email')
			],
			'telefono'      => 'max:12',
			'cargo'         => 'max:255',
			'departamento'  => 'max:255',
			'user_id'       => 'exists:users,id'
		]);

		$usuario->update($request->all());

		return response()
			->json([
				'updated' => true
			]);
	}

	public function updateAvatar(Request $request)
	{
		$usuario = User::find($request->user_id);
		$exploded = explode(',', $request->image);
		$image = base64_decode($exploded[1]);
		$filename = $this->getFileName($request->image);
		// Para ahorrar espacio
		if($usuario->avatar && Storage::exists('public/usuarios/avatars/' . $usuario->avatar)){
			Storage::delete('public/usuarios/avatars/' . $usuario->avatar);
		}
		Storage::put('/public/usuarios/avatars/' . $filename, $image);
		$usuario->avatar = $filename;
		$usuario->save();
		return response()
			->json([
				'updated' => true
			]);
	}

	public function getUsuario($user_id)
	{
		$usuario = User::with('tareas', 'tareas.usuario', 'tareas.asignadoA', 'archivos')->find($user_id);
		$usuario->tareas_asignadas = $usuario->tareasAsignadas()->with('usuario', 'asignadoA')->get();
		return response()
			->json([
				'usuario' => $usuario
			]);
	}

	public function changePassword(Request $request)
	{
		$this->validate($request, [
			'password'      => 'required|min:5|confirmed',
			'user_id'       => 'exists:users,id'
		]);
		$usuario = User::find($request->user_id);
		$usuario->password = bcrypt($request->password);
		$usuario->save();

		return response()
			->json([
				'saved' => true
			]);
	}

	public function changeType(Request $request)
	{
		$usuario = User::find($request->id);
		if ($usuario->type == 'admin') {
			$usuario->type = 'member';
		} else {
			$usuario->type = 'admin';
		}
		$usuario->save();
		return response()
			->json([
				'update'	=> true,
				'newType'	=> $usuario->type
			]);
	}

	public function destroy($id)
	{
		User::find($id)->delete();
		return response()
			->json([
				'deleted'   => true
			]);
	}

	public function getFileName($base64)
    {
        $exploded = explode(',', $base64);
        if(str_contains($exploded[0], '/')){
            $extension = explode(';', explode('/', explode(',', $base64)[0])[1])[0];
            return date('Y') . '/' . str_random() . '.' . $extension;
        }
        return false;
    }
}
