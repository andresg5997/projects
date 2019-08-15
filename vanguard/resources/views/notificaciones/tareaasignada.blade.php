@extends('notificaciones.layout.main')

@section('content')
	<div class="container">
		<div class="card z-depth-2">
			<div class="card-content">
				<p>{{ $fecha }}</p>
				<h5>{{ $tarea->usuario->fullName() }} te ha asignado la tarea {{ $tarea->titulo }}</h5><br>
				<a class="btn blue waves-effect waves-light" href="{{route('tareas.show', $tarea->id) }}">Ver tarea</a>
			</div>
		</div>
	</div>
@endsection
