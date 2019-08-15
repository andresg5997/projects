@extends('layout.main')
@section('title', 'Configuración de Dashboard')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/vue-multiselect.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
	.cursor{
		cursor: pointer;
	}
</style>
@endsection

@section('content')
<div id="app">
	{{-- Modal Nuevo Panel --}}
	<div id="dashboardModal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4>Crear nuevo panel</h4>
			<div class="row">
				<form class="col s12" enctype="multipart/form-data" @submit.prevent="submit">
					<div class="row">
						<div class="col s6">
							<p>Seleccione el proceso</p>
							<multiselect @select="selectProceso($event)"
							:hide-selected="true" :options="procesos"
							label="nombre" placeholder="Escoger proceso"
							select-label="Presiona enter para elegir"
							selected="Elegido"
							deselect-label="Presiona enter para quitar"
							>
							</multiselect>
						</div>
						<template v-if="showEstados == 1">
							<div class="col s6">
							<p>Elija el estado</p>
								<multiselect @select="selectEstado($event)"
								:hide-selected="true" :options="procesoEstados"
								label="nombre" placeholder="Escoger estado"
								select-label="Presiona enter para elegir"
								selected="Elegido"
								deselect-label="Presiona enter para quitar"
								>
								</multiselect>
							</div>
						</template>
					</div>
					<div class="row">
						<div class="col s6">
							<p>Elija el plazo de tiempo (en días)</p>
							<input type="number" min="0" v-model="form.dias_estado">
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="btn-flat waves-effect" @click="closeNew()">Cancelar</a>
			<button type="submit" class="btn blue waves-effect waves-light" @click="submit">Guardar</button>
		</div>
	</div>
	{{-- End Modal Nuevo Panel --}}
	{{-- Modal Editar Panel --}}
	<div id="dashboardEditModal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4>Editar panel</h4>
			<div class="row">
				<form class="col s12" enctype="multipart/form-data" @submit.prevent="editPanel()">
					<div class="row">
						<div class="col s6">
							<p>Seleccione el proceso</p>
							<multiselect @select="editProceso($event)"
							:hide-selected="true" :options="procesos"
							label="nombre" placeholder="Escoger proceso"
							select-label="Presiona enter para elegir"
							selected="Elegido" :value="selectedProcesoEdit"
							deselect-label="Presiona enter para quitar"
							>
							</multiselect>
						</div>
						<div class="col s6">
						<p>Elija el estado</p>
							<multiselect @select="selectEstado($event)"
							:hide-selected="true" :options="procesoEstadosEdit"
							label="nombre" placeholder="Escoger estado"
							select-label="Presiona enter para elegir"
							selected="Elegido" :value="selectedEstadoEdit"
							deselect-label="Presiona enter para quitar"
							>
							</multiselect>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<p>Elija el plazo de tiempo (en días)</p>
							<input type="number" min="0" v-model="form.dias_estado">
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="btn-flat waves-effect" @click="closeEdit()">Cancelar</a>
			<button type="submit" class="btn blue waves-effect waves-light" @click="editPanel()">Guardar</button>
		</div>
	</div>
	{{-- End Modal Editar Panel --}}
	<div class="container">
		<div class="card">
			<div class="card-content">
				<div class="row">
					<a class="btn-floating btn-large hoverable waves-effect waves-light blue right" @click="botonAdd()">
						<i class="material-icons">add</i>
					</a>
				</div>
				<div class="row" v-if="dashboards.length > 0" v-for="(dashboard, index) in dashboards">
					<div class="col s10">
						<div class="card z-depth-2" style="border: 1px solid rgba(0,0,0,0.5)">
							<div class="card-content">
								<h4>Panel #@{{ index+1 }}</h4>
								<p style="font-size: 20px">Mostrar los clientes que hayan tenido el estado
								<b>"@{{ dashboard.estado.nombre }}"</b>
								 en los últimos
								<b>@{{ dashboard.dias_estado }} días</b>
								</p>
							</div>
						</div>
					</div>
					<div class="col s2">
						<i class="material-icons blue-text text-darken-4 medium cursor tooltipped"
						data-position="bottom" data-delay="50" data-tooltip="Editar" @click="botonEdit(index)">edit</i>
						<i class="material-icons red-text text-darken-4 medium cursor tooltipped"
						data-position="bottom" data-delay="50" data-tooltip="Quitar"
						@click="quitarPanel(index)">remove</i>
					</div>
				</div>
				<h5 v-if="dashboards.length === 0">No hay paneles.</h5>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
  window.Laravel = {!! json_encode([
   'csrfToken' => csrf_token(),
  ]) !!};
</script>
<script type="text/javascript">
	var storage = {
		csrf_token: '{{ csrf_token() }}',
		dashboards: {!! $dashboards !!},
		procesos: {!! $procesos !!},
		estados: {!! $estados !!}
	}
</script>
<script type="text/javascript" src="{{ asset('js/config/dashboard.js') }}"></script>
@endsection
