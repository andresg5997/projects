import Vue from 'vue'
import axios from 'axios'
import swal from 'sweetalert2'

const app = new Vue({
	el: '#app',
	data: {
		usuarios: []
	},
	created(){
		this.getData()
	},
	methods: {
		getData(){
			axios.get(laroute.route('api.usuarios'))
				.then((res) => {
					this.usuarios = res.data.usuarios
				})
		},
		remove(usuario){
			swal({
				title: '¿Estás seguro?',
				text: "Esta acción no se puede deshacer.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Borrar'
			}).then(function () {
				axios.delete(laroute.route('api.usuario', {id: usuario.id}))
					.then((res) => {
						if(res.data.deleted){
							swal(
								'Hecho',
								'Usuario eliminado correctamente.',
								'success'
							)
							app.usuarios.splice(app.usuarios.indexOf(usuario), 1)
							return;
						}
					})
					.catch((error) => {
						swal('Algo ocurrió!', '', 'error')
					})
			})
		},
		changeType(usuario){

			swal({
				html: usuario.nombre,
				title: 'Seleccione tipo de usuario',
				input: 'select',
				inputOptions: {
					'admin': 'Administrador',
					'member': 'Miembro'
				},
				inputPlaceholder: 'Seleccione tipo',
				showCancelButton: true,
				inputValidator: function (value) {
					return new Promise(function (resolve, reject) {
						if (usuario.type === value) {
							reject('Este tipo de usuario ya está asignado.')
						} else {
							if ((value === 'admin')||(value === 'member')) {
								resolve()
							} else {
								reject('Debes seleccionar algo.')
							}
						}
					})
				}
			}).then(function (result) {
				axios.post(laroute.route('api.changeType'), usuario)
					.then((res) => {
						if(res.data.update){
							swal({
								type: 'success',
								html: 'Se ha cambiado el tipo de usuario:<br><br><b>' + result + '<b>'
							})
							usuario.type = res.data.newType
						}
					}).catch((error) =>{
						swal('Algo ocurrió!', '', 'error')
					})
			})
			.catch(swal.noop);
		},
		nombreCompleto(usuario){
			if(usuario.apellido == null){
				return usuario.nombre
			} else {
				return usuario.nombre + ' ' + usuario.apellido
			}
		},
		laroute(route, params){
            if(params){
                return laroute.route(route, params)
            }
            return laroute.route(route)
        }
	},
	computed: {
	}
})
