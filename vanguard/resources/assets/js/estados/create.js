import Vue from 'vue'
import axios from 'axios'
import toastr from 'toastr'
import Multiselect from 'vue-multiselect'
import swal from 'sweetalert2'

new Vue({
	el: '#app',
	components: {
		Multiselect
	},
	data: {
		form: {
			nombre: '',
			proceso: '',
			estado_posterior: [],
			requisitos: [
				{ nombre: '', tipo: '', opciones: [] }
			],
			titulo_tarea: '',
			tiempo_seguimiento: ''
		},
		tipos: [
			{nombre: 'Texto', tipo: 'text', index: 0},
			{nombre: 'Fecha', tipo: 'date', index: 1},
			{nombre: 'Archivo', tipo: 'file', index: 2},
			{nombre: 'Seleccion', tipo: 'select', index: 3},
			{nombre: 'Mapa', tipo: 'map', index: 4},
			{nombre: 'Automatizada', tipo: 'auto', index: 5},
			{nombre: 'Campo de cliente', tipo: 'cliente', index: 6}
		],
		tipos_campo_cliente: [
			{nombre: 'Nombre', tipo: 'nombre'},
			{nombre: 'Apellido', tipo: 'apellido'},
			{nombre: 'Correo Electrónico', tipo: 'email'},
			{nombre: 'Ciudad', tipo: 'ciudad'},
			{nombre: 'País', tipo: 'pais'},
			{nombre: 'Dirección', tipo: 'direccion'}
		],
        tipos_auto: [
            {nombre: 'Correo Electronico', tipo: 'email', tipo_tarea: '', tipo_fecha: '', fecha: ''},
            {nombre: 'Mensaje de Texto', tipo: 'sms', tipo_tarea: '', tipo_fecha: '', fecha: ''}
        ],
        tipo_fecha_auto: [
            {nombre: 'Al hacer la tarea', tipo: 'tarea'},
            {nombre: 'Seleccionar tiempo', tipo: 'fecha'}
        ],
		tipo_tarea_email: [],
		tipo_tarea_sms: [],
		procesos: [],
		estados: [],
		errors: {},
		proceso: {},
		lockProceso: 0,
		showEstados: ''
	},
	created(){
		this.getData()
	},
	methods: {
		getData(){
			axios.get(laroute.route('api.procesos'))
				.then((res) => {
					res.data.procesos.forEach((proceso) =>{
						let obj = {
							nombre: proceso.nombre,
							id: proceso.id
						}
						this.procesos.push(obj)
					})
				})
			axios.get(laroute.route('api.correos'))
				.then((res) => {
					this.tipo_tarea_email = res.data.correos
				})
			if(storage.proceso_id){
				var obj = {
					nombre: storage.proceso_nombre,
					id: storage.proceso_id
				}
				this.form.proceso = obj
				this.lockProceso = 1
				this.getEstados()
			}
		},
		agregarRequisito(index){
			this.form.requisitos.push({
				nombre: '',
				tipo: '',
				opciones: []
			})
		},
		agregarOpcion(index){
			this.form.requisitos[index].opciones.push({
				nombre: ''
			})
		},
		quitarRequisito(index){
			if(this.form.requisitos.length > 1){
				this.form.requisitos.splice(index, 1)
				return
			}
			toastr.error('Debe haber al menos un requisito.')
		},
		agregarTarea(){
			this.form.tareas.push({
				titulo: ''
			})
		},
        quitarOpcion(index, indexOpc){
            this.form.requisitos[index].opciones.splice(indexOpc, 1)
        },
        selectCorreo(index, event){
        	let obj = {
        		id: event.id,
        		correo: event.correo_destino
        	}
        	this.form.requisitos[index].opciones.tipo_tarea = obj
        },
		store(){
			axios.post(laroute.route('estados.store'), this.form)
				.then((res) => {
					if(res.data.saved){
						swal('El estado se ha guardado con éxito!', '', 'success')
						setTimeout(() => {
							window.location = laroute.route('procesos.show', {proceso: this.form.proceso.id})
						}, 1500)
						return
					}
				})
				.catch((error) => {
					this.errors = error.response.data.errors
				})
		},
		getEstados(){
			this.estados = []
			axios.get(laroute.route('api.estados'))
				.then((res) => {
					res.data.estados.forEach((estado) => {
						if(this.form.proceso.id == estado.proceso_id){
							let obj = {
								nombre: estado.nombre,
								id: estado.id
							}
							this.estados.push(obj)
						}
					})
					this.showEstados = 1
				})
		},
		cambiarTipo(index, event){
			this.form.requisitos[index].tipo = event.tipo
			this.form.requisitos[index].index = event.index
		}
	}
})
