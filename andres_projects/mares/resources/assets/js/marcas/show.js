import Vue from 'vue'
import axios from 'axios'
import { getMarca } from './../helpers/api'
import moment from 'moment'
import VueDatepicker from 'vue-datepicker'

new Vue({
	el: '#app',
	components: { VueDatepicker },
	data: {
		archivos: {
			archivos: [],
			csrf_token: storage.csrf_token,
			user_id: storage.auth_id
		},
		option: {
			type: 'day',
			week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
			month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			format: 'YYYY-MM-DD'
		},
		marca: {
			nombre: '',
			codigo: '',
			signo_distintivo: '',
			solicitante: '',
			clase: '',
			nro_inscripcion: '',
			nro_registro: '',
			fecha_vencimiento: '',
			distincion_producto_servicio: '',
			lema_comercial: '',
			tareas: [],
			archivos: [],
			transacciones: [],
		},
		edit: false,
		errors: {},
		form: {
			csrf_token: storage.csrf_token,
			lema_comercial: '',
			solicitante: '',
			codigo: '',
			clase: '',
			nro_inscripcion: '',
			nro_registro: '',
			fecha_vencimiento: {
				time: ''
			},
			distincion_producto_servicio: ''
		},
		auth_id: storage.auth_id,
		type: storage.type
	},
	created(){
		this.getData()
	},
	methods: {
		getData(){
			axios.get(laroute.route('api.marca', {id: storage.marca_id}))
				.then((res) => {
					this.marca = res.data.marca
					this.form.lema_comercial = this.marca.lema_comercial
					this.form.solicitante = this.marca.solicitante
					this.form.codigo = this.marca.codigo
					this.form.clase = this.marca.clase
					this.form.nro_incripcion = this.marca.nro_incripcion
					this.form.nro_registro = this.marca.nro_registro
					this.form.distincion_producto_servicio = this.marca.distincion_producto_servicio
				})
		},
		editMarca(){
			axios.put('/api/marcas/' + storage.marca_id, this.form)
				.then((res) => {
					if(res.data.updated){
						this.edit = false
						this.marca.fecha_vencimiento = this.form.fecha_vencimiento['time']
						toastr.success('Los datos de la marca fueron actualizados.')
						this.getData()
					}
				}).catch((error) => {
					this.errors = error.response.data.errors
				})
		},
		showEdit(){
			this.edit = !this.edit
			if(this.edit){
				this.form.fecha_vencimiento = {
					'time': this.marca.fecha_vencimiento
				}
			}
		},
		laroute(route, params){
            if(params){
                return laroute.route(route, params)
            }
            return laroute.route(route)
        },
        filesChanged(e){
			this.archivos.archivos = []
			if(e.target.files.length > 0){
				for(let i = 0; i < e.target.files.length; i++){
					let reader = new FileReader()
					reader.readAsDataURL(e.target.files[i])
					reader.onload = () => {
						let archivo = {
							archivo: reader.result,
							titulo: e.target.files[i].name
						}
						this.archivos.archivos.push(archivo)
					}
				}
				return;
			}
		},
		submit() {
			console.log('Sent')
			axios.post(laroute.route('home') + 'api/marcas/' + this.marca.id + '/subirArchivos', this.archivos)
			.then((res) => {
				if(res.data.uploaded){
					toastr.success('Se subieron con éxito ' + res.data.total + ' archivos y ' + res.data.failed + ' dieron error.')
					this.archivos.archivos = []
					setTimeout(() => {
						window.location = this.laroute('marcas.show', {marca: this.marca.id})
					}, 2000)
				}
			})
		}
	},
	computed: {
		check(){
			if(this.type == 'admin'){
				return true
			}
			return false
		},
        archivosFiltrados(){
            return this.marca.archivos.filter((archivo) => archivo.usuario)
        }
	},
	filters: {
		moment(date){
			return moment(date).format('DD-MM-YYYY')
		},
		fromNow(date){
			return moment(date).locale('es').fromNow()
		},
		filter(date){
				return moment(date).format('DD-MM-YYYY')
		},
		humanize(str){
				var frags = str.split('_')
				for (let i = 0; i < frags.length; i++) {
						frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1)
				}
				return frags.join(' ')
		}
	}
})
