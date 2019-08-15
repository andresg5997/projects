import Vue from 'vue'
import axios from 'axios'
import jquery from 'jquery'
import toastr from 'toastr'
import moment from 'moment'
import { getUsuarios, getTareas, getBase64, getMarcas } from './helpers/api'
import VueDatepicker from 'vue-datepicker'
import _ from 'lodash'

Vue.directive("material-select", {
	bind: function(el, binding, vnode) {
	  $(function() {
		$(el).material_select();
	  });
	  var arg = binding.arg;
	  if (!arg) arg = "change";
	  arg = "on" + arg;
	  el[arg] = function() {
		vnode.context.$data.form.asignado = el.value;
	  };
	},
	unbind: function(el) {
	  $(el).material_select("destroy");
	}
});

Vue.directive("material-select-marcas", {
	bind: function(el, binding, vnode) {
	  $(function() {
		$(el).material_select();
	  });
	  var arg = binding.arg;
	  if (!arg) arg = "change";
	  arg = "on" + arg;
	  el[arg] = function() {
		vnode.context.$data.form.marca_id = el.value;
	  };
	},
	unbind: function(el) {
	  $(el).material_select("destroy");
	}
});

const app = new Vue({
	el: '#app',
	components: {
		VueDatepicker
	},
	data: {
		tareas: [],
		marcas: [],
		form: {
			user_id: storage.user_id,
			csrf_token: storage.csrf_token,
			titulo: '',
			descripcion: '',
			estado: '0',
			asignado: '0',
			fecha_vencimiento: {
				time: ''
			},
			archivos: [],
			marca_id: '0'
		},
		usuarios: [],
		errors: {},
		isProcessing: false,
		option: {
			type: 'day',
			week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
			month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			format: 'YYYY-MM-DD'
		}
	},
	created() {
		this.getData()
		setTimeout(() => $('select').material_select(), 2000)
	},
	methods: {
		getData(){
			getTareas(this.form.user_id)
			.then((res) => {
				this.tareas = _.orderBy(res.data.tareas, 'fecha_vencimiento', 'ASC')
			})

			getMarcas(this.form.user_id)
			.then((res) => {
				this.marcas = res.data.marcas
			})

			getUsuarios(this.form.user_id)
			.then((res) => {
				this.usuarios = res.data.usuarios
				$('select').material_select();
			})
		},
		dateUpdated(e){
			console.log(e)
		},
		submit(){
			this.errors = []
			this.isProcessing = true
			axios.post(laroute.route('tareas.index'), this.form)
			.then((res) => {
				toastr.success('La tarea se ha guardado exitosamente.')
				$('#taskModal').modal('close')
				this.tareas.unshift(res.data.tarea)
				this.form.titulo = ''
				this.form.descripcion = ''
				this.form.fecha_vencimiento = {
					time: ''
				}
				this.form.asignado = '0'
				this.form.archivos = []

				if(res.data.fileErrors > 0){
					toastr.error(res.data.fileErrors + ' archivos no se pudieron guardar por no tener extensión.')
				}
				this.isProcessing = false
				$('select').material_select()
			})
			.catch((error) => {
				this.isProcessing = false
				this.errors = error.response.data.errors
				toastr.error('Ocurrió un error al guardar la tarea.')
			})
		},
		checkTarea(tarea){
			let now = parseInt(new Date().setHours(0,0,0,0))
			let tareaTime = parseInt(new Date(tarea.fecha_vencimiento).setHours(0,0,0,0))
			if((now == tareaTime) || (now < tareaTime)){
				if(tarea.status === '0'){
					return true
				}
			}
			return false
		},
		statusClass(tarea){
			let now = parseInt(new Date().setHours(0,0,0,0))
			let tareaTime = parseInt(new Date(tarea.fecha_vencimiento).setHours(0,0,0,0))

			if(now > tareaTime){
				// console.log('Passed!')
				return {
					red: true,
					'darken-1': true
				}
			} else if (now == tareaTime){
				// console.log('On time!')
				return {
					yellow: true,
					'darken-2': true
				}
			}
			else{
				// console.log('Not yet passed')
				return {
					green: true,
					'darken-1': true
				}
			}
		},
		statusText(tarea){
			let now = parseInt(new Date().setHours(0,0,0,0))
			let tareaTime = parseInt(new Date(tarea.fecha_vencimiento).setHours(0,0,0,0))

			if(now > tareaTime){
				// console.log('Passed!')
				return 'Vencida'
			} else if (now == tareaTime){
				// console.log('On time!')
				return 'Para hoy'
			}
			else{
				// console.log('Not yet passed')
				return 'Pendiente'
			}
		},
		update(tarea, event){
			if(tarea.estado === '0'){
				this.updateTask(tarea, '1')
			}else{
				this.updateTask(tarea, '0')
			}
		},
		updateTask(tarea, value){
			axios.put(laroute.route('tareas.show', {id: tarea.id}), {estado: value})
				.then((res) => {
					this.tareas[this.tareas.indexOf(tarea)].estado = res.data.tarea.estado
					toastr.success('La tarea se ha actualizado con éxito!')
				})
				.catch((error) => {
					console.log(error.response.data)
				})
		},
		filesChanged(e){
			this.form.archivos = []
			if(e.target.files.length > 0){
				for(let i = 0; i < e.target.files.length; i++){
					console.log(e.target.files[i].name)
					let reader = new FileReader()
					reader.readAsDataURL(e.target.files[i])
					reader.onload = () => {
						let archivo = {
							archivo: reader.result,
							titulo: e.target.files[i].name
						}
						this.form.archivos.push(archivo)
					}
				}
				return;
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
		tareasIncompletas(){
			return this.tareas.filter((tarea) => tarea.status === '0')
		}
	},
	filters: {
		format(date){
			return moment(date).format('DD/MM/YY')
		},
		fromNow(date){
			return moment(date).locale('es').fromNow()
		}
	}
})
