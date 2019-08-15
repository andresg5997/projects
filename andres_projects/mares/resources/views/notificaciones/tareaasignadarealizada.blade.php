@extends('notificaciones.layout.main')

@section('content')
	<div class="container">
		<div class="card z-depth-2">
			<div class="card-content">
				<p>{{ $fecha }}</p>
				<h5>La tarea {{ $tarea->titulo }} asignada a {{ $tarea->asignadoA->fullName() }} fue realizada</h5><br>
				<a class="btn blue waves-effect waves-light" href="{{route('tareas.show', $tarea->id) }}">Ver tarea</a>
			</div>
		</div>
	</div>
@endsection
