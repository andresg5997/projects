@extends('notificaciones.layout.main')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col s12">
				<em>Correo enviado el {{ date('d/m/Y') }}</em>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m6">
				<div class="card-panel z-depth-2">
					<h4>Marcas negadas en los últimos {{$dias['dias_negadas']}} días</h4>
					@if(count($marcas_negadas) > 0)
					  <div class="row">
						  <div class="col s12">
							  <table class="responsive-table highlight bordered">
								<thead>
								  <th></th>
								  <th>Nombre</th>
								  <th>Estado</th>
								  <th>Última actualización</th>
								</thead>
								<tbody>
								  @foreach($marcas_negadas as $marca)
									<tr>
									  <td class="center">
										<img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
									  </td>
									  <td>{{ $marca->nombre }}</td>
									  <td>{{ $marca->estado }}</td>
									  <td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
									</tr>
								  @endforeach
								</tbody>
							  </table>
						  </div>
					  </div>
					@else
					  <div class="row">
						<div class="col s12">
						  <hr>
						  <strong>Sin resultados.</strong>
						</div>
					  </div>
					@endif
				</div>
			</div>
			<div class="col s12 m6">
				<div class="card-panel z-depth-2">
					<h4>Marcas con orden de publicación en los últimos {{$dias['dias_publicacion']}} días</h4>
					@if(count($marcas_publicacion) > 0)
					<div class="row">
						<div class="col s12">
							<table class="responsive-table highlight bordered">
							  <thead>
								<th></th>
								<th>Nombre</th>
								<th>Estado</th>
								<th>Última actualización</th>
							  </thead>
							  <tbody>
								@foreach($marcas_publicacion as $marca)
								  <tr>
									<td class="center">
									  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
									</td>
									<td>{{ $marca->nombre }}</td>
									<td>{{ $marca->estado }}</td>
									<td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
								  </tr>
								@endforeach
							  </tbody>
							</table>
						</div>
					</div>
					@else
					  <div class="row">
						<div class="col s12">
						  <hr>
						  <strong>Sin resultados.</strong>
						</div>
					  </div>
					@endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12 m6">
				<div class="card-panel z-depth-2">
					<h4>Marcas concedidas en los últimos {{$dias['dias_concedidas']}} días</h4>
					@if(count($marcas_concedidas) > 0)
					<div class="row">
						<div class="col s12">
							<table class="responsive-table highlight bordered">
							  <thead>
								<th></th>
								<th>Nombre</th>
								<th>Estado</th>
								<th>Última actualización</th>
							  </thead>
							  <tbody>
								@foreach($marcas_concedidas as $marca)
								  <tr>
									<td class="center">
									  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
									</td>
									<td>{{ $marca->nombre }}</td>
									<td>{{ $marca->estado }}</td>
									<td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
								  </tr>
								@endforeach
							  </tbody>
							</table>
						</div>
					</div>
					@else
					  <div class="row">
						<div class="col s12">
						  <hr>
						  <strong>Sin resultados.</strong>
						</div>
					  </div>
					@endif
				</div>
			</div>
			<div class="col s12 m6">
				<div class="card-panel z-depth-2">
					<h4>Solicitudes devueltas en los últimos {{$dias['dias_devueltas']}} días</h4>
					@if(count($marcas_devueltas) > 0)
					<div class="row">
						<div class="col s12">
							<table class="responsive-table highlight bordered">
							  <thead>
								<th></th>
								<th>Nombre</th>
								<th>Estado</th>
								<th>Última actualización</th>
							  </thead>
							  <tbody>
								@foreach($marcas_devueltas as $marca)
								  <tr>
									<td class="center">
									  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
									</td>
									<td>{{ $marca->nombre }}</td>
									<td>{{ $marca->estado }}</td>
									<td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
								  </tr>
								@endforeach
							  </tbody>
							</table>
						</div>
					</div>
					@else
					  <div class="row">
						<div class="col s12">
						  <hr>
						  <strong>Sin resultados.</strong>
						</div>
					  </div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
