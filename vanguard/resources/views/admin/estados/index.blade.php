@extends('layout.main')
@section('title', 'Estados de Marca')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/vue-multiselect.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
	<br>
	<div id="app" class="container">
		<div class="card">
			<div class="card-content">
				<div class="row">
					<div class="col s3">
						<h5>Lista de Estados</h5>
					</div>
					<div class="col s6">
						<multiselect v-model="proceso_select" :hide-selected="true" :options="procesos"
						label="nombre" placeholder="Escoger proceso para filtrar los estados"
						select-label="Presiona enter para elegir"
						:value="procesos.id" selected="Elegido"
						deselect-label="Presiona enter para quitar"
						></multiselect>
					</div>
					<div class="col s2 offset-s1">
						<a href="/estados/create" class="btn blue right">Crear nuevo</a>
					</div>
				</div>
				<table class="table-responsive bordered highlight">
					<thead>
						<th>ID</th>
						<th><i class="material-icons left">assignment</i> Nombre</th>
						<th><i class="material-icons left">archive</i> Requisitos</th>
						<th><i class="material-icons left">forward</i>Estado posterior</th>
						<th><i class="material-icons left">assistant</i> Acciones</th>
					</thead>
					<tbody>
						{{-- Para mostrar todos los estados --}}
						<tr v-for="(estado, index) in estados" v-if="proceso_select.id === -1">
							<td>@{{ estado.id }}</td>
							<td><a :href="laroute('home') + 'estados/' + estado.id + '/edit'" title="">@{{ estado.nombre }}</a></td>
							<td>
								<p v-for="requisito in estado.requisitos">
									@{{ requisito.nombre }}: @{{ requisito.tipo }}
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
								<a :href="laroute('home') + 'estados/' + estado.id + '/edit'" class="tooltipped" data-tooltip="Editar" data-position="bottom" data-delay="50">
									<i class="material-icons">mode_edit</i>
								</a>
								<a href="#!" @click="deleteEstado(estado.id, index)" class="red-text">
									<i class="material-icons">remove</i>
								</a>
							</td>
						</tr>
						{{-- Para mostrar los estados filtrados --}}
						<tr v-for="(estado, index) in estados" v-if="proceso_select.id == estado.proceso_id">
							<td>@{{ estado.id }}</td>
							<td><a :href="laroute('home') + 'estados/' + estado.id + '/edit'" title="">@{{ estado.nombre }}</a></td>
							<td>
								<p v-for="requisito in estado.requisitos">
									@{{ requisito.nombre }}: @{{ requisito.tipo }}
								</p>
							</td>
							<td>
								<p v-if="estado.posteriores.length > 0" v-for="posterior in estado.posteriores">
									@{{ posterior.nombre }}
								</p>
							</td>
							<td>
								<a :href="laroute('home') + 'estados/' + estado.id + '/edit'" class="tooltipped" data-tooltip="Editar" data-position="bottom" data-delay="50">
									<i class="material-icons">mode_edit</i>
								</a>
								<a href="#!" @click="deleteEstado(estado.id, index)" class="red-text">
									<i class="material-icons">remove</i>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	<script>
		const storage = {
			csrf_token: '{{ csrf_token() }}'
		}
	</script>
	<script type="text/javascript" src="{{ asset('js/estados/index.js') }}"></script>
@endsection
