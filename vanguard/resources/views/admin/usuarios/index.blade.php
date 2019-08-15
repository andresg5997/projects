@extends('layout.main')
@section('title', 'Usuarios')

@section('styles')
<style type="text/css">
	.full-width{
		width: 100%
	}

</style>
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
<div id="app">
	<div class="container">
		<div class="card z-depth-2">
			<div class="card-content">
				<h5>Lista de usuarios</h5>
				<table class="responsive-table highlight bordered">
					<thead>
						<th>ID</th>
						<th>Tipo</th>
						<th>Nombre</th>
						<th>Correo electrónico</th>
						<th>Cargo</th>
						<th>Departamento</th>
						<th>Teléfono</th>
						<th>Acciones</th>
					</thead>
					<tbody>
						<tr v-for="usuario in usuarios">
							<td>@{{ usuario.id }}</td>
							<td><span class="badge left white-text" :class="{'red darken-3': usuario.type == 'admin', 'yellow darken-3': usuario.type != 'admin'}">@{{ usuario.type }}</span></td>
							<td>@{{ nombreCompleto(usuario) }}</td>
							<td><a :href="laroute('home') + 'usuarios/' + usuario.id">@{{ usuario.email }}</a></td>
							<td>@{{ usuario.cargo }}</td>
							<td>@{{ usuario.departamento }}</td>
							<td>@{{ usuario.telefono }}</td>
							<td align="center">
								<table>
									<tr>
										<td>
											<button @click="remove(usuario)" class="btn-floating red darken-3 waves-effect waves-light" style="font-size: 24px; font-weight: bold;">&times;</button>
										</td>
										<td>
											<button @click="changeType(usuario)" class="btn-floating yellow darken-3 waves-effect waves-light"><i class="material-icons">account_circle</i></button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	const storage= {
		auth_id: '{{ Auth::user()->type }}'
	}
</script>
<script src="{{ asset('js/usuarios/index.js') }}"></script>
@endsection
