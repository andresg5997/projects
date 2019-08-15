@extends('layout.main')
@section('title', $marca->nombre)

@section('styles')
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
	<div class="row">
		<div class="col m3">
			<div class="card grey lighten-4">
				<form @submit.prevent="editMarca">
					<div class="center">
						<img :src="laroute('home') + 'storage/marcas/' + marca.signo_distintivo" class="circle responsive-img" alt="" v-if="marca.signo_distintivo">
					</div>
					<div class="card-content">
						<h4 class="center">@{{ marca.nombre }}</h4>
						<br>
						<button type="button" v-show="check" @click="showEdit" class="btn blue waves-effect waves-light">Editar</button>
						<div v-if="edit" class="input-field">
							<b>Lema comercial</b>:<br>
							<input placeholder="Lema Comercial" type="text" v-model="form.lema_comercial">
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.lema_comercial">
								<div class="col s12">
									<i class="material-icons left blue-text">flag</i>
									<b>Lema comercial</b>:<br> @{{ marca.lema_comercial }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Solicitante</b>:<br>
							<input placeholder="Solicitante" type="text" v-model="form.solicitante">
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.solicitante">
								<div class="col s12">
									<i class="material-icons left blue-text">person_pin</i>
									<b>Solicitante</b>:<br> @{{ marca.solicitante }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Código</b>:<br>
							<input placeholder="Código" type="text" v-model="form.codigo">
							<small class="red-text" v-if="errors.codigo">@{{ errors.codigo[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.codigo">
								<div class="col s12">
									<i class="material-icons left blue-text">confirmation_number</i>
									<b>Código</b>:<br> @{{ marca.codigo }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Clase</b>:<br>
							<input placeholder="Clase" type="text" v-model="form.clase">
							<small class="red-text" v-if="errors.clase">@{{ errors.clase[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.clase">
								<div class="col s12">
									<i class="material-icons left blue-text">class</i>
									<b>Clase</b>:<br> @{{ marca.clase }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Número de Inscripción</b>:<br>
							<input placeholder="# de Inscripción" type="text" v-model="form.nro_incripcion">
							<small class="red-text" v-if="errors.nro_inscripcion">@{{ errors.nro_inscripcion[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.nro_incripcion">
								<div class="col s12">
									<i class="material-icons left blue-text">business</i>
									<b>Número de Inscripción</b>:<br> @{{ marca.nro_incripcion }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Número de registro</b>:<br>
							<input placeholder="# de Registro" type="text" v-model="form.nro_registro">
							<small class="red-text" v-if="errors.nro_registro">@{{ errors.nro_registro[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.nro_registro">
								<div class="col s12">
									<i class="material-icons left blue-text">copyright</i>
									<b>Número de registro</b>:<br> @{{ marca.nro_registro }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Fecha de vencimiento</b>:<br>
							<vue-datepicker placeholder="Fecha de vencimiento" :date="form.fecha_vencimiento" :option="option"></vue-datepicker>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.fecha_vencimiento && form.fecha_vencimiento != 'nada'">
								<div class="col s12">
									<i class="material-icons left blue-text">event</i>
									<b>Fecha de vencimiento</b>:<br> @{{ marca.fecha_vencimiento }}
								</div>
							</div>
						</template>
						<div v-if="edit" class="input-field">
							<b>Distinción de producto o servicio</b>:<br>
							<textarea class="materialize-textarea" placeholder="Distinción" v-model="form.distincion_producto_servicio"></textarea>
							<small class="red-text" v-if="errors.distincion_producto_servicio">@{{ errors.distincion_producto_servicio[0] }}</small>
						</div>
						<template v-else>
							<div class="row card card-padding hoverable grey lighten-2" v-if="marca.distincion_producto_servicio">
								<div class="col s12">
									<i class="material-icons left blue-text">fitness_center</i>
									<b>Distinción de producto o servicio</b>:<br>
									<p>@{{ marca.distincion_producto_servicio }}</p>
								</div>
							</div>
						</template>
						<button v-show="edit" type="submit" class="btn blue waves-effect waves-light">Enviar</button>
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
														<div v-if="dato.tipo == 'file'">
															<b>@{{ dato.requisito | humanize }}</b>:<br>
															<a :href="laroute('home') + 'storage/archivos/' + dato.valor">@{{ dato.requisito }}</a>
														</div>
														<div v-else>
															<b>@{{ dato.requisito | humanize }}</b>:<br>
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
