@extends('notificaciones.layout.main')

@section('content')
	<div class="container">
		<div class="card z-depth-2">
			<div class="card-content">
				<p>{{ $fecha }}</p>
				<h5>El cliente {{ $marca->nombre }} fue editada.</h5><br>
				<a class="btn blue waves-effect waves-light" href="{{ route('marcas.show', $marca->id) }}">Ver marca</a>
			</div>
		</div>
	</div>
@endsection
