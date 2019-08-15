@extends('layout.main')
@section('title', 'Estados de Marca')

@section('styles')
	<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.0.6/dist/vue-multiselect.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
<br>
<div id="app" class="container">
	<div class="card">
		<div class="card-content">
			<div class="row">
				<div class="col s6">
					<h5>Editar estado</h5>
					<form @submit.prevent="store" v-if="estado">
						<div class="input-field">
							<label for="nombre">Nombre de estado</label>
							<input type="text" name="nombre" id="nombre" v-model="form.nombre" placeholder="Nombre de estado">
							<span class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</span>
						</div>
					</div>
					<div class="card col s6">
						<div class="card-content">
							<b>Estado anterior</b>
							<p v-for="anterior in estado_anterior" v-if="anterior">@{{ anterior.nombre }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s8">
						<label>Estado(s) posterior(es)</label>
						<multiselect v-model="form.estado_posterior" :hide-selected="true" :options="estados" label="nombre" :multiple="true" placeholder="Escoger opciones"
						select-label="Presiona enter para elegir"
						selected="Elegido"
						deselect-label="Presiona enter para quitar"
						>
						</multiselect>
					</div>
				</div>
				<div class="divider"></div>
				<br><br>
				<div class="row">
					<div class="col s6">
						<button type="button" @click="agregarRequisito" class="btn blue right">Añadir requisito</button>
						<h5>Requisitos</h5>
						<br><br>
						<strong>Lista de requisitos</strong><br>
						<span class="red-text" v-if="Object.keys(errors).length > 1">
							El nombre y tipo de todos los requisitos son requeridos.
						</span>
						<p v-if="errors.requisitos" class="red-text">Los @{{ errors.requisitos[0] }}</p>
						<div class="row" v-for="(requisito, index) in form.requisitos">
							<div class="col s1 valign-wrapper">
								<a href="#!" @click="quitarRequisito(index)" data-tooltip="Quitar" class="right red-text tooltipped" data-position="bottom" data-delay="50" style="font-size: 48px">
									&times;
								</a>
							</div>
							<div class="col s5 input-field">
								<i class="material-icons prefix">assignment</i>
								<label :for="'requisito' + index">Requisito @{{ index + 1 }}</label>
								<input :id="'requisito' + index" :name="'requisito' + index" type="text" v-model="form.requisitos[index].nombre" placeholder="Nombre">
							</div>
							<div class="col s5">
								<multiselect @select="cambiarTipo(index, $event)" :hide-selected="true" :options="tipos"
								:value="form.requisitos[index]"
								label="nombre" placeholder="Escoger tipo"
								select-label="Presiona enter para elegir"
								selected="Elegido"
								deselect-label="Presiona enter para quitar"
								:show-labels="false"
								>
								</multiselect>
							</div>
						</div>
					</div>
					<div class="col s6">
						<button type="button" @click="agregarTarea" class="btn blue right">Añadir tarea</button>
						<h5>Subtareas</h5>
						<br><br>
						<strong>Lista de tareas</strong>
						<div class="row" v-if="form.tareas.length > 0" v-for="(tarea, index) in form.tareas">
							<div class="col s1 valign-wrapper">
								<a href="#!" @click="quitarTarea(index)" data-tooltip="Quitar" data-position="bottom" data-delay="50" class="right red-text tooltipped" style="font-size: 48px">
									&times;
								</a>
							</div>
							<div class="col s11 input-field">
								<i class="material-icons prefix">checkbox</i>
								<label :for="'tarea' + index">Tarea @{{ index + 1 }}</label>
								<input type="text" :id="'tarea' + index" v-model="form.tareas[index].titulo" placeholder="Título de tarea">
							</div>
						</div>
						<div v-else>
							<br><br>
							<strong>Este estado se creará sin subtareas.</strong>
						</div>
					</div>
				</div>
				<button type="submit" class="btn blue">Enviar</button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script>
		const storage = {
			csrf_token: '{{ csrf_token() }}',
			estado_id: {{ $estado->id }}
		}
	</script>
	<script type="text/javascript" src="{{ asset('js/estados/edit.js') }}"></script>
@endsection
