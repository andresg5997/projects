@extends('layout.main')
@section('title', $marca->nombre)

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/vue-multiselect.min.css') }}">
<style type="text/css" media="screen">
.card-padding{
	padding: 10px 0px;
}
.tab-content{
	padding: 30px 0px 0px 0px;
}
</style>
@endsection

@section('content')
<br>
<div id="app">
	{{-- Modal Nuevo Proceso --}}
	<div id="procesoModal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4>Iniciar un nuevo proceso</h4>
			<div class="row">
				<div class="col s10 offset-s1">
					<multiselect @select="selectProceso($event)"
					:hide-selected="true" :options="procesos"
					label="nombre" placeholder="Escoger proceso"
					select-label="Presiona enter para elegir"
					selected="Elegido"
					deselect-label="Presiona enter para quitar">
					</multiselect>
				</div>
				<p class="col s12" v-if="selectedProceso.estados.length > 0"><b>Estados:</b></p>
				<div class="col s6">
					<span
					v-for="(estado, index) in selectedProceso.estados"
					v-if="index < selectedProceso.estados.length/2">
						<b>#@{{ index+1 }}</b>. @{{ estado.nombre }}<br>
					</span>
				</div>
				<div class="col s6">
					<span
					v-for="(estado, index) in selectedProceso.estados"
					v-if="index >= selectedProceso.estados.length/2">
						<b>#@{{ index+1 }}</b>. @{{ estado.nombre }}<br>
					</span>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="btn-flat waves-effect modal-close">Cancelar</a>
			<button type="submit" class="btn blue waves-effect waves-light" @click="iniciarProceso()">Guardar</button>
		</div>
	</div>
	{{-- End Modal Nuevo Proceso --}}
	{{-- Modal Borrar Marca --}}
	<div id="borrarModal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4 align="center" style="height: 30%;display: flex;align-items: center;justify-content: center;">¿Seguro que deseas borrar este cliente?</h4>
			<div class="row" style="height: 40%;display: flex;align-items: center;justify-content: center;">
				<div class="col s6">
					<center>
						<a href="#!" class="btn-flat waves-effect modal-close">Cancelar</a>
					</center>
				</div>
				<div class="col s6">
					<center>
						<button type="submit" class="btn red waves-effect waves-light" @click="borrarCliente()">Borrar cliente</button>
					</center>
				</div>
			</div>
		</div>
	</div>
	{{-- End Modal Borrar Marca --}}
	<div class="row">
		<div class="col m3">
			<div class="card grey lighten-4">
				<form @submit.prevent="editMarca">
					<div class="card-content">
						<center>
							<a class="btn waves-effect blue hoverable modal-trigger" href="#procesoModal">Iniciar un proceso</a>
						</center>
						<h4 class="center" v-if="!edit">@{{ marca.nombre }} @{{ marca.apellido }}</h4>
						<br>
						<button type="button" v-show="check" @click="showEdit" class="btn blue waves-effect waves-light">Editar</button>
						<a class="btn waves-effect red modal-trigger" href="#borrarModal" v-if="edit">Borrar cliente</a>
						<div v-if="edit" class="input-field">
							<b>Nombre</b>:<br>
							<input placeholder="Nombre" type="text" v-if="edit" v-model="form.nombre">
						</div>
						<div v-if="edit" class="input-field">
							<b>Apellido</b>:<br>
							<input placeholder="Apellido" type="text" v-model="form.apellido">
						</div>
						<div v-if="edit" class="input-field">
							<b>Correo Electrónico</b>:<br>
							<input placeholder="Email" type="text" v-model="form.email">
							<small class="red-text" v-if="errors.email">@{{ errors.email[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.email">
								<div class="col s12">
									<i class="material-icons left blue-text">contact_mail</i>
									<b>Correo Electrónico</b>:<br> @{{ marca.email }}
								</div>
							</div>
						</template>
						<template v-if="edit">
							<div class="input-field">
								<b>Teléfono</b>:<br>
								<input placeholder="Teléfono" type="text" v-model="form.telefono">
							</div>
							<template v-for="(dato, index) in configDatosAdicionales">
								<div class="input-field" v-if="dato.categoria == 'telefono'">
									<b>Teléfono (@{{dato.nombre}})</b>:<br>
									<input placeholder="Teléfono" type="text" v-model="form.datosAdicionales[index].valor">
								</div>
							</template>
						</template>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.telefono">
								<div class="col s12">
									<i class="material-icons left blue-text">contact_phone</i>
									<b>Teléfono</b>:<br> @{{ marca.telefono }}
								</div>
								<template v-for="(dato, index) in configDatosAdicionales">
									<div class="col s12" v-if="dato.categoria == 'telefono'">
										<template v-if="datosAdicionales[index]">
											<b>@{{ datosAdicionales[index].nombre }}</b>: <br> @{{ datosAdicionales[index].valor }}
										</template>
										<template v-else>
											<b>@{{ dato.nombre }}</b>: <br> *No hay datos*
										</template>
									</div>
								</template>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Ciudad</b>:<br>
							<input placeholder="Ciudad" type="text" v-model="form.ciudad">
							<small class="red-text" v-if="errors.ciudad">@{{ errors.ciudad[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.ciudad">
								<div class="col s12">
									<i class="material-icons left blue-text">business</i>
									<b>Ciudad</b>:<br> @{{ marca.ciudad }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>País</b>:<br>
							<input placeholder="País" type="text" v-model="form.pais">
							<small class="red-text" v-if="errors.pais">@{{ errors.pais[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.pais">
								<div class="col s12">
									<i class="material-icons left blue-text">card_travel</i>
									<b>País</b>:<br> @{{ marca.pais }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Número de Identificación</b>:<br>
							<input placeholder="# de Identificación" type="text" v-model="form.nro_identificacion">
							<small class="red-text" v-if="errors.nro_identificacion">@{{ errors.nro_inscripcion[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.nro_identificacion">
								<div class="col s12">
									<i class="material-icons left blue-text">dialpad</i>
									<b>Número de Identificación</b>:<br> @{{ marca.nro_identificacion }}
								</div>
							</div>
						</template>
						<template v-if="edit">
							<div class="input-field">
								<b>Dirección</b>:<br>
								<input placeholder="Dirección" type="text" v-model="form.direccion">
								<small class="red-text" v-if="errors.direccion">@{{ errors.direccion[0] }}</small>
							</div>
							<template v-for="(dato, index) in configDatosAdicionales">
								<div class="input-field" v-if="dato.categoria == 'direccion'">
									<b>Direccion (@{{dato.nombre}})</b>:<br>
									<input placeholder="Dirección" type="text" v-model="form.datosAdicionales[index].valor">
								</div>
							</template>
						</template>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.direccion">
								<div class="col s12">
									<i class="material-icons left blue-text">confirmation_number</i>
									<b>Dirección</b>:<br> @{{ marca.direccion }}
								</div>
								<template v-for="(dato, index) in configDatosAdicionales">
									<div class="col s12" v-if="dato.categoria == 'direccion'">
										<template v-if="datosAdicionales[index]">
											<b>@{{ datosAdicionales[index].nombre }}</b>: <br> @{{ datosAdicionales[index].valor }}
										</template>
										<template v-else>
											<b>@{{ dato.nombre }}</b>: <br> *No hay datos*
										</template>
									</div>
								</template>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Fecha de nacimiento</b>:<br>
							<vue-datepicker placeholder="Fecha de nacimiento" :date="form.fecha_nacimiento" :option="option"></vue-datepicker>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.fecha_nacimiento && form.fecha_nacimiento != 'nada'">
								<div class="col s12">
									<i class="material-icons left blue-text">event</i>
									<b>Fecha de nacimiento</b>:<br> @{{ marca.fecha_nacimiento }}
								</div>
							</div>
						</template>
						<template v-if="showOtrosDatos">
							<template v-for="(dato, index) in configDatosAdicionales" v-if="edit">
								<div class="input-field" v-if="dato.categoria == 'otro'">
									<b>@{{dato.nombre}}</b>:<br>
									<input placeholder="Adicional" type="text" v-model="form.datosAdicionales[index].valor">
								</div>
							</template>
							<div class="row card card-padding hoverable grey lighten-2" v-if="!edit">
								<div class="col s12">
									<i class="material-icons left blue-text">data_usage</i>
								</div>
								<template v-for="(dato, index) in configDatosAdicionales">
									<div class="col s12" v-if="dato.categoria == 'otro'">
										<template v-if="datosAdicionales[index]">
											<b>@{{ datosAdicionales[index].nombre }}</b>: <br> @{{ datosAdicionales[index].valor }}
										</template>
										<template v-else>
											<b>@{{ dato.nombre }}</b>: <br> *No hay datos*
										</template>
									</div>
								</template>
							</div>
						</template>
						<button v-show="edit" type="submit" class="btn blue waves-effect waves-light">Guardar</button>
					</div>
				</form>
			</div>
		</div>
		<div class="col m9">
			<div class="card">
				<div class="card-tabs">
					<ul class="tabs tabs-fixed-width">
						<li class="tab"><a class="active blue-text" href="#tareas">Tareas</a></li>
						<li class="tab"><a class="blue-text" href="#transacciones">Cronología de eventos</a></li>
						<li class="tab"><a class="blue-text" href="#archivos">Archivos</a></li>
					</ul>
				</div>
				<div class="card-content">
					<div class="tab-content">
						<div class="row">
							<div id="tareas" class="col s12">
								<ul class="collapsible" data-collapsible="accordion" v-show="marca.tareas.length > 0">
									<li v-for="tarea in marca.tareas">
										<div class="collapsible-header hoverable">
											@{{ tarea.titulo }}
											<span v-if="tarea.status === '1'" class="badge green white-text">Realizada</span>
											<span v-else class="badge red white-text">No realizada</span>
										</div>
										<div class="collapsible-body">
											<div class="row">
												<div class="col s3">
													<i class="material-icons left blue-text">today</i>
													<b>Fecha de vencimiento</b>:
													@{{ tarea.fecha_vencimiento | moment}}
													(@{{ tarea.fecha_vencimiento | fromNow}})
												</div>
												<div class="col s3">
													<template v-if="tarea.status === '1'">
														<i class="material-icons left green-text">check_box</i>
														<b class="green-text">Realizada</b>
													</template>
													<template v-else>
														<i class="material-icons left">check_box_outline_blank</i>
														<b class="red-text">No realizada</b>
													</template>
												</div>
												<div class="col s3" v-if="tarea.asignado_a">
													<i class="material-icons left blue-text">assignment_ind</i>
													<b>Asignado a</b>:
													<a :href="laroute('home') + 'usuarios/' + tarea.asignado_a.id">
														@{{ tarea.asignado_a.nombre }}
													</a>
												</div>
												<div class="col s3">
													<i class="material-icons left black-text">assignment_ind</i>
													<b>Creada por</b>:
													<a :href="laroute('home') + 'usuarios/' + tarea.usuario.id">
														<template v-if="tarea.usuario.apellido">
															@{{ tarea.usuario.nombre  + ' ' + tarea.usuario.apellido}}
														</template>
														<template v-else>
															@{{ tarea.usuario.nombre }}
														</template>
													</a>
												</div>
											</div>
											<p>
												@{{ tarea.descripcion }}
											</p>
											<br>
											<a class="btn blue" :href="laroute('home') + 'tareas/' + tarea.id">Ir a tarea <i class="material-icons right">send</i></a>
										</div>
									</li>
								</ul>
								<h4 v-if="marca.tareas.length === 0">No hay tareas</h4>
							</div>
							<div id="transacciones" class="col s12">
								<h5>Historial de movimientos</h5>
									<table class="responsive-table striped highlight bordered" v-if="marca.transacciones && marca.transacciones.length > 0">
										<thead>
											<th>ID</th>
											<th>Estado</th>
											<th>Usuario</th>
											<th>Datos</th>
											<th>Fecha</th>
										</thead>
										<tbody>
											<tr v-for="transaccion in marca.transacciones">
												<td>@{{ transaccion.id }}</td>
												<td>@{{ transaccion.estado.nombre }}</td>
												<td>@{{ transaccion.usuario.nombre }}</td>
												<td>
													<span v-if="transaccion.datos.length == 0">
														Sin datos.
													</span>
													<template v-else v-for="dato in transaccion.datos">
														<b>@{{ dato.requisito | humanize }}</b>:<br>
														<div v-if="dato.tipo == 'file'">
															<a :href="laroute('home') + 'storage/archivos/' + dato.valor">@{{ dato.requisito }}</a>
														</div>
														<div v-if="dato.tipo == 'cliente'">
															<span v-if="dato.valor.nombre">@{{ dato.valor.nombre }}</span>
															<span v-if="dato.valor.apellido">@{{ dato.valor.apellido }}</span>
															<span v-if="dato.valor.email">@{{ dato.valor.email }}</span>
															<span v-if="dato.valor.ciudad">@{{ dato.valor.ciudad }}</span>
															<span v-if="dato.valor.pais">@{{ dato.valor.pais }}</span>
															<span v-if="dato.valor.direccion">@{{ dato.valor.direccion }}</span>
														</div>
														<div v-else>
															<span>@{{ dato.valor }}</span>
														</div>
													</template>
												</td>
												<td>@{{ transaccion.fecha | fromNow }} (@{{ transaccion.fecha | filter }})</td>
											</tr>
										</tbody>
								</table>
								<p v-else>
									No hay transacciones todavía.
								</p>
							</div>
							<div id="archivos" class="col s12">
								<div class="row">
									<form @submit.prevent="submit">
										<div class="col s12">
											<h5>Cargar nuevo(s) archivo(s)</h5>
											<div class="file-field input-field">
												<div class="btn blue">
													<span>Subir</span>
													<input type="file" multiple @change="filesChanged">
												</div>
												<div class="file-path-wrapper">
													<input class="file-path" type="text" placeholder="Subir uno o más archivos">
												</div>
											</div>
										</div>
										<div class="col s12 center">
											<button type="submit" class="btn blue waves-light waves-effect">Cargar</button>
										</div>
									</form>
								</div>
								<br><br>
								<h5>Archivos</h5>
								<table class="responsive-table striped highlight bordered" v-if="marca.archivos && marca.archivos.length > 0">
								  <thead>
									<th>Título</th>
									<th>Tarea relacionada</th>
									<th>Subido por</th>
									<th>Fecha</th>
								  </thead>
								  <tbody>
									<tr v-for="archivo in archivosFiltrados">
									  <td>
										<i class="material-icons left">file_download</i>
										<a :download="archivo.titulo" :href="laroute('home') + 'storage/archivos/' + archivo.nombre_archivo">
										  @{{ archivo.titulo }}
										</a>
									  </td>
									  <td v-if="archivo.tarea">
										<a :href="laroute('home') + 'tareas/' + archivo.tarea.id">@{{ archivo.tarea.titulo }}</a>
									  </td>
									  <td v-else>
										Ninguna
									  </td>
									  <td>
										<a :href="laroute('home') + 'usuarios/' + archivo.usuario.id">@{{ archivo.usuario.nombre + ' ' + archivo.usuario.apellido }}</a>
									  </td>
									  <td>
										@{{ archivo.created_at | fromNow }} (@{{ archivo.created_at | filter }})
									  </td>
									</tr>
								  </tbody>
								</table>
								<div v-else>
									<h5>No hay archivos guardados para esta marca.</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
@endsection

@section('scripts')
<script type="text/javascript">
	var storage = {
		auth_id: {{ Auth::id() }},
		type: '{{ Auth::user()->type }}',
		csrf_token: '{{ csrf_token() }}',
		marca_id: {{ $marca->id }}
	}
</script>
<script type="text/javascript" src="{{ asset('js/marcas/show.js') }}"></script>
@endsection
