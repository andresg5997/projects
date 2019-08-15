import Vue from 'vue'
import axios from 'axios'
import moment from 'moment'
import toastr from 'toastr'
import VuePaginate from 'vue-paginate'
import _ from 'lodash'
Vue.use(VuePaginate)

new Vue({
	el: '#app',
	data: {
		result: '',
		usuario: {
			nombre: '',
			apellido: '',
			email: '',
			telefono: '',
			cargo: '',
			departamento: '',
			avatar: '',
			tareas: [],
			archivos: [],
			transacciones: []
		},
		auth_id: storage.auth_id,
		type: storage.type,
		user_id: storage.user_id,
		edit: false,
		editPassword: false,
		passForm: {
			csrf_token: storage.csrf_token,
			user_id: storage.user_id,
			password: '',
			password_confirmation: ''
		},
		form: {
			csrf_token: storage.csrf_token,
			user_id: storage.user_id,
			nombre: '',
			apellido: '',
			email: '',
			telefono: '',
			cargo: '',
			departamento: ''
		},
		avatarForm: {
			image: '',
			csrf_token: storage.csrf_token,
			user_id: storage.user_id
		},
		errors: {},
		showTareasArchivos: false,
		lista_archivos: [],
		lista_tareas: [],
		paginate: ['lista_archivos', 'lista_tareas']
	},
	created() {
		this.getData()
	},
	mounted() {
		setTimeout(() => {
			this.showTareasArchivos = true
		}, 700)
	},
	methods: {
		getData(){
			axios.get(laroute.route('api.usuario', {id: storage.user_id}))
				.then((res) => {
					this.usuario = res.data.usuario
					this.form.nombre = this.usuario.nombre
					this.form.apellido = this.usuario.apellido
					this.form.email = this.usuario.email
					this.form.telefono = this.usuario.telefono
					this.form.cargo = this.usuario.cargo
					this.form.departamento = this.usuario.departamento
					this.lista_tareas = _.orderBy(this.usuario.tareas, 'fecha_vencimiento', 'ASC')
					this.lista_archivos = this.usuario.archivos
				})
		},
		changePassword(){
			this.errors = {}
			axios.post(laroute.route('api.changePassword'), this.passForm)
				.then((res) => {
					if(res.data.saved){
						this.passForm.password = ''
						this.passForm.password_confirmation = ''
						toastr.success('La contraseña se ha cambiado con éxito.')
						return;
					}
					toastr.error('Algo ha pasado y no se pudo actualizar la contraseña.')

				}).catch((error) => {
					this.errors = error.response.data.errors
				})
		},
		editProfile(){
			axios.put(laroute.route('api.usuario', {id: this.user_id}), this.form)
				.then((res) => {
					if(res.data.updated){
						this.edit = false
						toastr.success('Los datos del usuario han sido actualizados.')
						return;
					}
					toastr.error('Algo ha pasado y no ese pudo editar los datos del usuario.')

				}).catch((error) => {
					console.log(error)
					this.errors = error.response.data.errors
				})
			this.usuario.nombre = this.form.nombre
			this.usuario.apellido = this.form.apellido
			this.usuario.email = this.form.email
			this.usuario.telefono = this.form.telefono
			this.usuario.cargo = this.form.cargo
			this.usuario.departamento = this.form.departamento
		},
		cambiarAvatar(event){
			var output = document.getElementById('output')
			var usuarioAvatar = document.getElementById('usuarioAvatar')
			var reader = new FileReader()
			reader.readAsDataURL(event.target.files[0])
			reader.onload = () => {
				this.avatarForm.image = reader.result
				output.src = reader.result
				usuarioAvatar.src = reader.result
			}
		},
		uploadAvatar(){
			axios.put(laroute.route('api.updateAvatar'), this.avatarForm)
				.then((res) => {
					toastr.success('La imagen se ha cambiado con éxito!')
					$('#modal1').modal('close');
				})
				.catch((error) => {
					toastr.error('Ha ocurrido un error. Por favor inténtalo de nuevo.')
				})
		},
		mostrarPerfil(){
			if(this.editPassword){
				this.editPassword = false
				this.edit = true
			}else{
				this.edit = !this.edit
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
		nombreCompleto(){
			if(this.usuario.apellido != null){
				return this.usuario.nombre + ' ' + this.usuario.apellido
			}
			return this.usuario.nombre
		},
		check(){
			if(this.type == 'admin' || this.user_id === this.auth_id){
				return true
			}
			return false
		},
		tareasCompletadas(){
			return this.lista_tareas.filter((tarea) => tarea.estado == '1')
		}
	},
    filters: {
        moment(date){
            return moment(date).format('DD-MM-YYYY')
        },
        fromNow(date){
            return moment(date).locale('es').fromNow()
        }
    }
})
