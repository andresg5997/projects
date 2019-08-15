@extends('layout.main')
@section('title', 'Procesos')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
<div id="app">
	<div class="container">
		<div class="card">
			<div class="card-content">
				<div class="row">
					<h3 align="center">@{{ proceso.nombre }}</h3>
					<h5>@{{ proceso.descripcion }}</h5>
					<a :href="laroute('home') + 'estados/create/procesos/' + proceso.id" class="btn blue right">Crear nuevo estado</a>
					<template v-if="procesoEstados.length > 0">
						<table class="table-responsive bordered highlight">
							<thead>
								<th>#</th>
								<th><i class="material-icons left">assignment</i>Nombre</th>
								<th><i class="material-icons left">archive</i>Actividades</th>
								<th><i class="material-icons left">event</i>Tarea Principal</th>
								<th><i class="material-icons left">forward</i>Estado posterior</th>
								<th><i class="material-icons left">assistant</i> Acciones</th>
							</thead>
							<tbody>
								<tr v-for="(estado, index) in procesoEstados">
									<td>@{{ index+1 }}</td>
									<td><a :href="laroute('home') + 'estados/' + estado.id + '/edit/procesos/' + proceso.id" title="">@{{ estado.nombre }}</a></td>
									<td>
										<p v-for="requisito in estado.requisitos">
											@{{ requisito.nombre }}: @{{ requisito.tipo }}
										</p>
									</td>
									<td>
										<p>
											@{{ estado.titulo_tarea }}
										</p>
									</td>
									<td>
										<p v-if="estado.posteriores.length > 0" v-for="posterior in estado.posteriores">
											<template v-if="posterior">
												@{{ posterior.nombre }}
											</template>
										</p>
									</td>
									<td>
										<a :href="laroute('home') + 'estados/' + estado.id + '/edit/procesos/' + proceso.id" class="tooltipped" data-tooltip="Editar" data-position="bottom" data-delay="50">
											<i class="material-icons">mode_edit</i>
										</a>
										<a href="#!" @click="deleteEstado(estado.id, index)" class="red-text">
											<i class="material-icons">remove</i>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</template>
				</div>
				<h5 v-if="procesoEstados.length === 0" align="center">No hay estados en este proceso.</h5>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	var storage = {
		proceso_id: {{ $id }},
		csrf_token: '{{ csrf_token() }}'
	}
</script>
<script type="text/javascript" src="{{ asset('js/procesos/show.js') }}"></script>
@endsection
