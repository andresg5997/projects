@extends('layout.main')
@section('title', 'Perfil')

@section('styles')
<style>
.titulo-perfil{
	margin-left: 25px;
	padding-top: 20px
}
.avatar-perfil{
	width: 190px;
	height: 190px;
	display: flex;
	align-items:center;
	justify-content: center;
	text-align: center;
	margin: auto;
}
.icono-perfil{
	margin-top: .82rem;
}
.texto-perfil{
	margin-top: 1rem!important;
}
.tarea-descripcion{
	padding-bottom: 2rem;
}
.no-padding{
	padding: 0;
}
.full-width{
	width: 100%;
}

</style>
@endsection

@section('content')
<br>
<div id="app">
	<div id="modal1" class="modal modal-fixed-footer">
		<form @submit.prevent="uploadAvatar" enctype="multipart/form-data">
			<div class="modal-content">
				<h5>Cargar nuevo avatar</h5>
				<div class="file-field input-field">
					<div class="btn blue">
						<span>Cargar avatar</span>
						<input type="file" accept="image/*" @change="cambiarAvatar($event)">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<img id="output" class="responsive-img" src="">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="modal-action waves-effect waves-green btn-flat">Enviar</button>
			</div>
		</form>
	</div>
	<div class="container">
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s6">
								<h4>Perfil de Usuario</h4>
							</div>
							<div class="col s6">
								<button type="button" @click="mostrarPerfil()" v-show="check" class="btn blue waves-effect waves-light">Editar Perfil</button>
								<button type="button" @click="editPassword = !editPassword" v-show="check" class="btn blue waves-effect waves-light">Cambiar Contraseña</button>
							</div>
						</div>
						<div class="row">
							<div class="col s5">
								<div v-if="editPassword" class="card">
									<div class="card-content">
										<h5>Cambiar contraseña</h5>
										<form @submit="changePassword">
											<div class="input-field">
												<input id="password" type="password" v-model="passForm.password">
												<label for="password">Contraseña</label>
											</div>
											<small class="red-text" v-if="errors.password">@{{ errors.password[0] }}</small>
											<div class="input-field" style="display: block">
												<input id="password_confirmation" type="password" v-model="passForm.password_confirmation">
												<label for="password_confirmation">Confirmar contraseña</label>
											</div>
											<button type="submit" class="btn blue waves-effect">Enviar</button>
										</form>
									</div>
								</div>
								<div v-else class="card grey lighten-4">
									<div class="card-content">
										<div class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<i v-show="!edit" class="material-icons left small icono-perfil">account_box</i>
													<h5 class="left">Nombre</h5>
												</div>
												<div v-if="edit" class="input-field">
													<input placeholder="Nombre" type="text" v-model="form.nombre">
													<small class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
												</div>
												<p v-else class="texto-perfil">@{{ nombreCompleto }}</p>
											</div>
										</div>
										<div v-show="edit" class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<h5 class="left">Apellido</h5>
												</div>
												<div class="input-field">
													<input placeholder="Apellido" type="text" v-model="form.apellido">
													<small class="red-text" v-if="errors.apellido">@{{ errors.apellido[0] }}</small>
												</div>
											</div>
										</div>
										<div class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<i v-show="!edit" class="material-icons left small">contact_mail</i>
													<h5>Correo</h5>
												</div>
												<div v-if="edit" class="input-field">
													<input placeholder="Correo Electrónico" v-model="form.email" type="text">
													<small class="red-text" v-if="errors.email">@{{ errors.email[0] }}</small>
												</div>
												<p v-else class="texto-perfil">@{{ usuario.email }}</p>
											</div>
										</div>
										<div class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<i v-show="!edit" class="material-icons left small">contact_phone</i>
													<h5>Telefono</h5>
												</div>
												<div v-if="edit" class="input-field">
													<input placeholder="Telefono" v-model="form.telefono" type="text">
													<small class="red-text" v-if="errors.telefono">@{{ errors.telefono[0] }}</small>
												</div>
												<template v-else class="texto-perfil">
													<p v-if="usuario.telefono == null" class="texto-perfil">No hay teléfono</p>
													<p v-else class="texto-perfil">@{{ usuario.telefono }}</p>
												</template>
											</div>
										</div>
										<div class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<i v-show="!edit" class="material-icons left small icono-perfil">domain</i>
													<h5 class="left">Departamento</h5>
												</div>
												<div v-if="edit" class="input-field">
													<input placeholder="Departamento" v-model="form.departamento" type="text">
													<small class="red-text" v-if="errors.departamento">@{{ errors.departamento[0] }}</small>
												</div>
												<template v-else>
													<p v-if="usuario.departamento == null" class="texto-perfil">No hay departamento</p>
													<p v-else class="texto-perfil">@{{ usuario.departamento }}</p>
												</template>
											</div>
										</div>
										<div class="card z-depth-2">
											<div class="card-content">
												<div class="row">
													<i v-show="!edit" class="material-icons left small">event_seat</i>
													<h5>Cargo</h5>
												</div>
												<div v-if="edit" class="input-field">
													<input placeholder="Cargo" v-model="form.cargo" type="text">
													<small class="red-text" v-if="errors.cargo">@{{ errors.cargo[0] }}</small>
												</div>
												<template v-else class="texto-perfil">
													<p class="texto-perfil" v-if="usuario.cargo == null">No hay cargo</p>
													<p class="texto-perfil" v-else >@{{ usuario.cargo }}</p>
												</template>
											</div>
										</div>
										<button v-show="edit" type="submit" @click="editProfile" class="btn blue waves-effect waves-light">Enviar</button>
									</div>
								</div>
							</div>
							<div class="col s7">
								<div v-if="usuario.avatar" class="avatar-perfil">
									<img id="usuarioAvatar" :src="laroute('home') + 'storage/usuarios/avatars/' + usuario.avatar" height="200px" width="200px" class="circle">
								</div><br>
								<div class="row">
									<a class="btn blue waves-effect waves-light modal-trigger right" href="#modal1">Cambiar avatar</a>
								</div>
								<div class="card">
									<div class="card-tabs">
										<ul class="tabs tabs-fixed-width">
											<li class="tab"><a class="blue-text" href="#tareas">Tareas Completadas</a></li>
											<li class="tab"><a class="blue-text" href="#archivos">Archivos Subidos</a></li>
										</ul>
									</div>
									<div class="card-content">
										<div id="tareas">
											<paginate
											name="lista_tareas"
											:list="lista_tareas"
											:per="5"
											data-collapsible="accordion"
											class="collapsible"
											v-show="usuario.tareas.length > 0 && showTareasArchivos"
											>
											<li v-for="tarea in paginated('lista_tareas')">
												<div class="collapsible-header hoverable">
													<div class="row full-width">
														<div class="col s9">
															@{{ tarea.titulo }}
														</div>
														<div class="col s3 no-padding">
															<span v-if="tarea.status === '1'" class="badge green white-text">Realizada</span>
															<span v-else class="badge red white-text">No realizada</span>
														</div>
													</div>
												</div>
												<div class="collapsible-body">
													<div class="row">
														<i class="material-icons left blue-text">today</i>
														<b>Fecha de vencimiento</b>:
														@{{ tarea.fecha_vencimiento | moment}}
														(@{{ tarea.fecha_vencimiento | fromNow}})
													</div>
													<div class="row">
														<div class="col s4 no-padding">
															<template v-if="tarea.status === '1'">
																<i class="material-icons left green-text">check_box</i>
																<b class="green-text">Realizada</b>
															</template>
															<template v-else>
																<i class="material-icons left">check_box_outline_blank</i>
																<b class="red-text">No realizada</b>
															</template>
														</div>
														<div class="col s4" v-if="tarea.asignado_a">
															<i class="material-icons left blue-text">assignment_ind</i>
															<b>Asignado a</b>:
															<a :href="laroute('home') + 'usuarios/' + tarea.asignado_a.id">
																@{{ tarea.asignado_a.nombre }}
															</a>
														</div>
														<div class="col s4 no-padding">
															<i class="material-icons left black-text">assignment_ind</i>
															<b>Creada por</b>:
															<a v-if="tarea.usuario.apellido == null" :href="laroute('home') + 'usuarios/' + tarea.usuario.id">
																@{{ tarea.usuario.nombre}}
															</a>
															<a v-else :href="laroute('home') + 'usuarios/' + tarea.usuario.id">
																@{{ tarea.usuario.nombre  + ' ' + tarea.usuario.apellido}}
															</a>
														</div>
													</div>
													<p>
														@{{ tarea.descripcion }}
													</p>
													<br>
													<a class="btn" :href="laroute('home') + 'tareas/' + tarea.id">Ir a tarea <i class="material-icons right">send</i></a>
												</div>
											</li>
										</paginate>
										<paginate-links
										for="lista_tareas"
										v-show="usuario.tareas.length > 0 && showTareasArchivos"
										:simple="{
										prev: 'Anterior',
										next: 'Siguiente'
									}"
									:classes="{
									'ul': 'pagination',
									'.next > a': 'waves-effect',
									'.prev > a': 'waves-effect'
								}"
								></paginate-links>
								<h4 v-if="usuario.tareas.length === 0">No hay tareas</h4>
							</div>
							<div id="archivos">
								<paginate
								name="lista_archivos"
								:list="lista_archivos"
								:per="10"
								data-collapsible="accordion"
								class="collection"
								v-show="usuario.archivos.length > 0 && showTareasArchivos"
								>
								<li v-for="archivo in paginated('lista_archivos')" class="collection-item row">
									<i class="material-icons">file_download</i>
									<a :href="laroute('home') + 'storage/archivos/' + archivo.nombre_archivo" :download="archivo.titulo">@{{ archivo.titulo }}</a>
									<p class="right">
										@{{ archivo.created_at | moment }}
									</p>
								</li>
							</paginate>
							<paginate-links
							for="lista_archivos"
							v-show="usuario.archivos.length > 0 && showTareasArchivos"
							:simple="{
							prev: 'Anterior',
							next: 'Siguiente'
						}"
						:classes="{
						'ul': 'pagination',
						'.next > a': 'waves-effect',
						'.prev > a': 'waves-effect'
					}"
					></paginate-links>
					<h4 v-if="usuario.archivos.length === 0"> No hay archivos</h4>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
	const storage = {
		user_id: {{ $user_id }},
		auth_id: {{ Auth::id() }},
		type: '{{ Auth::user()->type }}',
		csrf_token: '{{ csrf_token() }}'
	}
</script>
<script src="{{ asset('js/usuarios/show.js') }}"></script>
@endsection
