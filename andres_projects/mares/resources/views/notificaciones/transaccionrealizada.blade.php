@extends('notificaciones.layout.main')

@section('content')
	<div class="container">
		<div class="card z-depth-2">
			<div class="card-content">
				<p>{{ $fecha }}</p>
				<h5>La marca {{$marca->nombre}} cambió de estado a {{$estado->nombre}}</h5><br>
				<a class="btn blue waves-effect waves-light" href="{{route('transacciones.show', $transaccion->id) }}">Ver</a>
			</div>
		</div>
	</div>
@endsection
