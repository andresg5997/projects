<template>
	<div class="row">
		<div class="col s12">
			<form @submit.prevent="submit">
				<div class="card">
					<div class="card-content">
						<h4>Proceso de cambio de estado</h4>
						<div class="row">
							<div class="col s12">
								<h5>Estado actual: <b>{{ estado.nombre }}</b></h5>
								<p><b>Para el cliente:</b> <a href="#">{{ tarea.marca.nombre }}</a></p>
							</div>
						</div>
						<div class="row">
							<div class="col s12">
								<h5>Requisitos</h5>
								<div v-if="hasMultiple" class="input-field">
									<p>Estado posterior:</p>
									<select id="posterior" v-material-select required>
										<option :value="estado.id" v-for="estado in posteriores">{{ estado.nombre }}</option>
									</select>
								</div>
								<template v-for="(requisito, index) in estado.requisitos">
									<div class="card">
										<div class="card-content">
											<div v-if="isFile(requisito.tipo)">
												<b>{{ humanize(requisito.nombre) }}</b>
												<div class="file-field input-field">
													<div class="btn blue">
														<span>Cargar</span>
														<input type="file" :name="requisito.nombre" @change="file(index, $event)" required>
													</div>
													<div class="file-path-wrapper">
														<input class="file-path validate" type="text">
													</div>
												</div>
											</div>

											<div class="input-field" v-else-if="requisito.tipo === 'date'">
												<b>{{ humanize(requisito.nombre) }}</b>
												<br>
												<vue-datepicker :option="option"
												:date="form.requisitos[index].valor"></vue-datepicker>
											</div>

											<div class="input-field" v-else-if="requisito.tipo === 'select'">
												<div class="row">
													<div class="col s4">
														<b>{{ humanize(requisito.nombre) }}</b>
													</div>
													<div class="col s8">
														<select v-material-requisito-select>
															<option value="" :onclick="saveOption(index)">Seleccione una opcion</option>
															<option :value="opcion.nombre" v-for="opcion in requisito.opciones" :onclick="saveOption(index)">
																<span>{{ opcion.nombre }}</span>
															</option>
														</select>
													</div>
												</div>
											</div>

											<div class="input-field" v-else-if="requisito.tipo === 'map'">
												<b>{{ humanize(requisito.nombre) }}</b>
												<br>
												<div class="row">
													<div class="col s8">
														<gmap-map
														:center="center"
														:zoom="7"
														@center_changed="updateCenter"
														style="width: 500px; height: 300px"
														>
														<gmap-marker
														:key="index"
														v-for="(m, index) in markers"
														:position="m.position"
														:clickable="true"
														:draggable="true"
														></gmap-marker>
													</gmap-map>
													<div class="row">
														<div class="input-field">
															<div class="col s7">
																<input type="text" v-model="searchInput">
															</div>
															<div class="col s5">
																<input type="button" value="Buscar" class="btn" @click="searchLocation()">
															</div>
														</div>
													</div>
												</div>
												<div class="col s4">
													<div class="card">
														<div class="card-content">
															<div>
																Lat: <input type="number" step="any" v-model="center.lat" @update="updateLocation(index)"/>
															</div>
															<div>
																Lng: <input type="number" step="any" v-model="center.lng" @update="updateLocation(index)"/>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="input-field" v-else-if="requisito.tipo === 'cliente'">
											<p><b>{{ humanize(requisito.nombre) }}</b> - {{ requisito.opciones.nombre }}</p>
											<input type="text" v-model="form.requisitos[index].valor[form.requisitos[index].opcion]">
										</div>

										<div class="input-field" v-else-if="requisito.tipo === 'auto'">
											<p><b>{{ humanize(requisito.nombre) }}</b> - {{ requisito.opciones.nombre }}</p>
											<br><p>Correo destino</p>
											<input type="email" placeholder="Ingrese correo destino" v-if="requisito.opciones.tipo_tarea.correo_destino == 'tarea'" v-model="form.requisitos[index].valor.correo_destino">
											<input v-else type="text" label="correo_destino" disabled :value="requisito.opciones.tipo_tarea.correo_destino">
											<p>Fecha de envio</p>
											<b v-if="requisito.opciones.tipo_fecha == 'tarea'">Al finalizar la tarea</b>
											<b v-else>{{ form.requisitos[index].valor.fecha }}</b>
										</div>

										<div class="input-field" v-else-if="requisito.tipo === 'error'">
											<p><b>{{ humanize(requisito.nombre) }}</b> - {{ requisito.opciones.nombre }}</p>
											<p>Este requisito tipo "Tarea automatizada" tiene errores para traer los datos necesarios, revise el estado para solucionar el problema</p>
										</div>

										<div class="input-field" v-else>
											<b>{{ humanize(requisito.nombre) }}</b><br>
											<input :type="requisito.tipo" :name="requisito.nombre" :id="requisito.nombre" @change="inputChanged(index, $event)" required>
										</div>

									</div>
								</div>
							</template>
						</div>
					</div>
					<div align="center">
						<button type="submit" class="btn blue btn-primary">Guardar</button>
						<p style="color: blue" v-if="procesamiento_pendiente">La tarea se está procesando, por favor espere...</p>
					</div>
				</div>
			</div>
			<div class="card" v-if="estado.estado_posterior">
				<div class="card-content indigo lighten-5">
					<div class="row" style="margin-top: 10px" v-if="form.estado_posterior">
						<div class="col s12">
							<h5>Siguiente estado:</h5>
						</div>
						<div class="col s4">
							<b>Nombre:</b><br>{{ estadoPosteriorNombre }}
						</div>
						<div class="col s8">
							<b>Tarea Principal:</b><br>{{ estadoPosteriorTarea }}
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col s4">
							<b>Fecha de vencimiento</b>
							<br>
							<vue-datepicker :date="form.fecha_vencimiento" :option="option"></vue-datepicker>
							<p v-if="errors.fecha_vencimiento" class="red-text">{{ errors.fecha_vencimiento[0] }}</p>
						</div>
						<div class="col s8">
							<b for="asignar">Asignar la tarea del siguiente estado a:</b>
							<div class="input-field">
								<select v-material-select-asignar id="asignar">
									<option value="0" selected>Nadie</option>
									<option :value="usuario.id" v-for="usuario in usuarios">{{ usuario.nombre + ' ' + usuario.apellido }}</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import VueDatepicker from 'vue-datepicker'
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
	load: {
		key: 'AIzaSyAATw7zkkc8lZjaBjtsv-7zUYJR1CRv0_w',
		libraries: 'places'
	}
})

Vue.directive("material-select", {
	bind: function(el, binding, vnode) {
		$(function() {
			$(el).material_select();
		});
		var arg = binding.arg;
		if (!arg) arg = "change";
		arg = "on" + arg;
		el[arg] = function() {
			vnode.context.$data.form.estado_posterior = parseInt(el.value);
		};
	},
	unbind: function(el) {
		$(el).material_select("destroy");
	}
});


Vue.directive("material-select-auto", {
	bind: function(el, binding, vnode) {
		$(function() {
			$(el).material_select();
		});
		var arg = binding.arg;
		if (!arg) arg = "change";
		arg = "on" + arg;
		el[arg] = function() {
			vnode.context.$data.tipoAutoTemporal = el.value;
		};
	},
	unbind: function(el) {
		$(el).material_select("destroy");
	}
});

Vue.directive("material-requisito-select", {
	bind: function(el, binding, vnode) {
		$(function() {
			$(el).material_select();
		});
		var arg = binding.arg;
		if (!arg) arg = "change";
		arg = "on" + arg;
		el[arg] = function() {
			vnode.context.$data.selectTemporal = el.value;
		};
	},
	unbind: function(el) {
		$(el).material_select("destroy");
	}
});

Vue.directive("material-select-asignar", {
	bind: function(el, binding, vnode) {
		$(function() {
			$(el).material_select();
		});
		var arg = binding.arg;
		if (!arg) arg = "change";
		arg = "on" + arg;
		el[arg] = function() {
			vnode.context.$data.form.asignar = parseInt(el.value);
		};
	},
	unbind: function(el) {
		$(el).material_select("destroy");
	}
});

export default {
	components: { VueDatepicker },
	props: ['tarea', 'user_id', 'csrf_token'],
	data(){
		return {
			center: {lat: 10.6355402, lng: -71.7170454},
			markers: [{
				position: {lat: 10.6355402, lng: -71.7170454}
			}],
			searchInput: '',
			requisitoMapIndex: [],
			correos: [],
			errors: {},
			estado: {
				requisitos: [],
				estado_posterior: '0'
			},
			selectTemporal: '',
			tipoAutoTemporal: '',
			valor: '',
			form: {
				csrf_token: this.csrf_token,
				user_id: parseInt(this.user_id),
				tarea_id: parseInt(this.tarea.id),
				estado_id: 0,
				requisitos: [],
				asignar: 0,
				estado_posterior: 0,
				marca_id: 0,
				fecha_vencimiento: {
					time: storage.hoy
				},
			},
			usuarios: [],
			posteriores: [],
			tarea_marca_id: {},
			procesamiento_pendiente: false,
			option: {
				type: 'day',
				week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
				month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				format: 'YYYY-MM-DD'
			}
		}
	},
	created(){
		// this.getTarea()
		this.getUsuarios()
		this.getData()
		this.form.marca_id = this.tarea.marca_id
		setTimeout(() => $('select').material_select(), 1000)
	},
	methods: {
		getTarea(){
			axios.get(laroute.route('api.tarea', {id: this.form.tarea_id}))
				.then((res) => {
					this.tarea = res.data.tarea
					this.form.marca_id = this.tarea.marca_id
					this.getData()
				})
				.catch((error) => {
					console.log(error.response)
				})
		},
		getUsuarios(){
			axios.get(laroute.route('api.usuario.usuarios', {id: this.form.user_id}))
				.then((res) => {
					this.usuarios = res.data.usuarios
				})
		},
		updateCenter(newCenter) {
			this.center = {
				lat: newCenter.lat(),
				lng: newCenter.lng(),
			}
			this.markers[0].position = {
				lat: newCenter.lat(),
				lng: newCenter.lng(),
			}
			if(this.requisitoMapIndex.length > 0){
				for (var i = 0; i < this.requisitoMapIndex.length; i++) {
					this.form.requisitos[this.requisitoMapIndex[i]].valor = this.center.lat + ',' + this.center.lng
				}
			}
		},
		searchLocation() {
			var geocoder = new google.maps.Geocoder()
			geocoder.geocode({'address': this.searchInput}, (results, status) => {
				if (status === 'OK') {
					this.center = {
						lat: results[0].geometry.location.lat(),
						lng: results[0].geometry.location.lng()
					}
					this.markers[0].position = {
						lat: results[0].geometry.location.lat(),
						lng: results[0].geometry.location.lng()
					}
				}
			})
		},
		ver(input) {
			console.log(input)
		},
		saveOption(index) {
			this.form.requisitos[index].valor = this.selectTemporal
		},
		cambiarTipoAuto(index){
			this.form.requisitos[index].valor.tipo = this.tipoAutoTemporal
		},
		inputChanged(index, event){
			this.form.requisitos[index].valor = event.target.value
		},
		getData(){
			axios.get(laroute.route('api.tarea', {id: storage.tarea_id}))
				.then((res) => {
					this.tarea_marca_id = res.data.tarea.marca_id
				})
			axios.get(laroute.route('api.correos'))
				.then((res) => {
					this.correos = res.data.correos
				})
			axios.get(laroute.route('api.estado', {estado: this.tarea.estado_id}))
				.then((res) => {
					this.estado = res.data.estado
					this.form.estado_id = this.estado.id
					this.estado.requisitos = this.parseRequisitos(this.estado.requisitos)
					if(!this.hasMultiple){
						this.form.estado_posterior = parseInt(this.estado.estado_posterior)
					} else {
						let parts = this.estado.estado_posterior.split(',')
						this.form.estado_posterior = parseInt(parts[0])
					}
					this.getPosteriores()
					let index = Object.keys(this.estado.requisitos)
					let requisitos = Object.values(this.estado.requisitos)
					for (var i = 0; i < index.length; i++) {
						if(requisitos[i].tipo == 'date'){
							var valor = {}
						}else{
							if(requisitos[i].tipo == 'map'){
								this.requisitoMapIndex.push(i);
							}else{
								if(requisitos[i].tipo == 'auto'){
									var date = new Date()
									var fecha = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate()
									if(requisitos[i].opciones.tipo_fecha == 'fecha'){
									  var fecha = new Date(fecha);
									  fecha.setDate(fecha.getDate() + parseInt(requisitos[i].opciones.fecha));
									  fecha = fecha.getFullYear() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getDate()
									}
									var valor = {
										tipo: requisitos[i].opciones.tipo_tarea.id,
										fecha: fecha
									}
								}else{
									var valor = ''
								}
							}
						}
						let obj = {}
						if(requisitos[i].tipo == 'cliente'){
							obj = {
								requisito: requisitos[i].nombre,
								tipo: requisitos[i].tipo,
								opcion: res.data.estado.requisitos[i].opciones.tipo,
								valor: {
									nombre: '',
									apellido: '',
									email: '',
									ciudad: '',
									pais: '',
									direccion: ''
								}
							}
						} else {
							obj = {
								requisito: requisitos[i].nombre,
								tipo: requisitos[i].tipo,
								valor: valor
							}
						}
						this.form.requisitos.push(obj)
					}
				})
		},
		parseRequisitos(requisitos){
			let requisito = Object.values(requisitos)
			let parsed = []
			requisito.forEach((valor, index) => {
				let obj = {}
				if(valor.tipo == 'auto'){
					var valor_opciones = valor.opciones
					if( typeof valor_opciones.tipo_fecha == 'undefined' || typeof valor_opciones.tipo_tarea == 'undefined' || typeof valor_opciones.fecha == 'undefined'){
						obj = {
							nombre: valor.nombre,
							tipo: 'error',
							opciones: {
								nombre: valor.opciones.nombre
							}
						}
					} else {
                    	let fecha = valor.opciones.fecha
                    	let email = {}
		                if(Number.isInteger(valor.opciones.tipo_tarea)){
							email = this.correos.filter((tarea_email) => tarea_email.id == parseInt(valor.opciones.tipo_tarea))[0]
		                } else {
							email = this.correos.filter((tarea_email) => tarea_email.id == parseInt(valor.opciones.tipo_tarea.id))[0]
		                }

						obj = {
							nombre: valor.nombre,
							tipo: valor.tipo,
							opciones: {
								fecha: fecha,
								nombre: valor.opciones.nombre,
								tipo: valor.opciones.tipo,
								tipo_fecha: valor.opciones.tipo_fecha,
								tipo_tarea: {
									correo_destino: email.correo_destino,
									id: email.id,
									id_plantilla: email.id_plantilla,
									variables: JSON.parse(email.variables)
								}
							},
							index: valor.index
						}
					}
				} else {
					obj = {
						nombre: valor.nombre,
						tipo: valor.tipo,
						opciones: valor.opciones,
						index: valor.index
					}
				}
				parsed.push(obj)
			})
			return parsed
		},
		isFile(tipo){
			if(tipo === 'file'){
				return true
			}
			return false
		},
		humanize(str){
			var frags = str.split('_')
			for (let i = 0; i < frags.length; i++) {
				frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1)
			}
			return frags.join(' ')
		},
		file(index, event){
			var fileReader = new FileReader()
			fileReader.readAsDataURL(event.target.files[0])

			fileReader.onload = (event) => {
				this.form.requisitos[index].valor = event.target.result
			}
		},
		submit(){
			this.procesamiento_pendiente = true
			if(!this.form.estado_posterior){
				this.form.estado_posterior = 0
			}
			axios.post(laroute.route('api.transacciones'), this.form)
				.then((res) => {
					if(res.data.saved){
						this.form.requisitos.forEach((requisito) => {
							if(requisito.tipo == 'cliente'){
								axios.put(laroute.route('api.marca.campo.update', {marca: this.tarea_marca_id}), requisito.valor)
								.then((res) => {
									if(res.data.updated){
										console.log('Cliente Updated')
									}
								})
							}
						})
						toastr.success('La transacción se ha realizado con éxito.')
						setTimeout(() => {
							window.location = laroute.route('home')
						}, 1500)
						return
						this.tarea.estado = false
						this.$emit('updated')
					}
					toastr.error('error')
					return
				})
				.catch((err) => {
					this.errors = err.response.data.errors
				})
		},
		getPosteriores(){
			let ids = this.estado.estado_posterior.split(',')
			ids.forEach((id) => {
				axios.get(laroute.route('api.estado', {estado: id}))
					.then((res) => {
						this.posteriores.push(res.data.estado)
					})
			})
		}
	},
	computed: {
		hasMultiple(){
			let result = this.estado.estado_posterior.indexOf(',')
			if(result === -1){
				return false
			}
			return true
		},
		estadoPosteriorNombre(){
			if(this.posteriores.filter((estado) => estado.id === this.form.estado_posterior)[0]){
				return this.posteriores.filter((estado) => estado.id === this.form.estado_posterior)[0].nombre
			}
		},
		estadoPosteriorTarea(){
			if(this.posteriores.filter((estado) => estado.id === this.form.estado_posterior)[0].titulo_tarea){
				return this.posteriores.filter((estado) => estado.id === this.form.estado_posterior)[0].titulo_tarea
			}
		}
	}
}
</script>
