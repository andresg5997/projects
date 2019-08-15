@extends('layout.main')
@section('title', 'Procesos')

@section('styles')
<style>
.proceso-card {
	border: 2px solid rgba(0,0,0,0.5);
	cursor: pointer;
}
.proceso-card h5{
	color: white;
}

</style>
@endsection

@section('content')
<div id="app">
	{{-- Modal --}}
	<div id="procesoModal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h5>Crear nuevo proceso</h5>
			<form enctype="multipart/form-data">
				<div class="row">
					<div class="col s12 input-field">
						<i class="material-icons prefix">dns</i>
						<label for="nombre">Nombre del proceso</label>
						<input type="text" id="nombre" v-model="form.nombre">
						<span class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</span>
					</div>
					<div class="col s12 input-field">
						<i class="material-icons prefix">description</i>
						<label for="descripcion">Descripción del proceso</label>
						<textarea class="materialize-textarea" id="descripcion" v-model="form.descripcion"></textarea>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
			<button class="btn blue waves-effect" @click="submit">Guardar</button>
		</div>
	</div>
	{{-- End Modal --}}
	<div class="container">
		<div class="card">
			<div class="card-content">
				<a href="#procesoModal" class="right btn-floating waves-effect waves-light blue btn-large modal-trigger"><i class="material-icons">add</i></a>
				<h5>Procesos</h5>
				<br>
				<div class="row">
					<template v-for="proceso in procesos">
						<div class="col s12 m6 l3">
							<a :href="laroute('home') + 'procesos/' + proceso.id">
								<div class="card hoverable proceso-card blue darken-4">
									<div class="card-content">
											<center><h5>@{{ proceso.nombre }}</h5></center>
									</div>
								</div>
							</a>
						</div>
					</template>
					<h4 v-if="procesos.length === 0" align="center">No hay procesos.</h4>
				</div>
			</div>
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
<script type="text/javascript" src="{{ asset('js/procesos/index.js') }}"></script>
@endsection
