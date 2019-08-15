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
								<p><b>Para la marca:</b> <a href="#">{{ tarea.marca.nombre }}</a></p>
							</div>
						</div>
						<div class="row">
							<div class="col s12">
								<h5>Requisitos</h5>
									<div class="input-field">
										<label v-if="hasMultiple" for="posterior">Estado posterior:</label>
										<select id="posterior" v-material-select v-if="hasMultiple" required>
											<option :value="estado.id" v-for="estado in posteriores">
												{{ estado.nombre }}
											</option>
										</select>
									</div>
									<template v-for="(tipo, requisito, index) in estado.requisitos">
										<div v-if="isFile(tipo)">
											<b>{{ humanize(requisito) }}</b>
											<div class="file-field input-field">
											  <div class="btn blue">
												<span>Cargar</span>
												<input type="file" :name="requisito" @change="file(index, $event)" required>
											  </div>
											  <div class="file-path-wrapper">
												<input class="file-path validate" type="text">
											  </div>
											</div>
										</div>

										<div class="input-field" v-else-if="tipo === 'date'">
											<b>{{ humanize(requisito) }}</b>
											<br>
											<vue-datepicker :option="option"
											:date="form.requisitos[index].valor"></vue-datepicker>
										</div>

										<div class="input-field" v-else>
											<label :for="requisito">{{ humanize(requisito) }}</label>
											<input :type="tipo" :name="requisito" :id="requisito" @change="inputChanged(index, $event)" required>
										</div>
									</template>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-content indigo lighten-5">
						<div class="row" style="margin-top: 10px">
							<div class="col s12" v-if="hasMultiple">
								<h5>Siguiente estado: {{ estadoPosterior }}</h5>
							</div>
						</div>
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
						<div align="center">
							<button type="submit" class="btn blue btn-primary">Guardar</button>
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
import toastr from 'toastr'
import moment from 'moment'
import VueDatepicker from 'vue-datepicker'

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
			errors: {},
			estado: {
				estado_posterior: '0'
			},
			form: {
				csrf_token: this.csrf_token,
				user_id: parseInt(this.user_id),
				tarea_id: parseInt(this.tarea.id),
				estado_id: 0,
				requisitos: [],
				asignar: 0,
				estado_posterior: 0,
				marca_id: 0,
				fecha_vencimiento: {}
			},
			usuarios: [],
			posteriores: [],
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
		inputChanged(index, event){
			this.form.requisitos[index].valor = event.target.value
		},
		getData(){
			axios.get(laroute.route('api.estado', {id: this.tarea.estado_id}))
				.then((res) => {
					this.estado = res.data.estado
					this.form.estado_id = this.estado.id

					if(!this.hasMultiple){
						this.form.estado_posterior = parseInt(this.estado.estado_posterior)
					} else {
						let parts = this.estado.estado_posterior.split(',')
						this.form.estado_posterior = parseInt(parts[0])
					}


					let requisitos = Object.keys(this.estado.requisitos)
					let tipos = Object.values(this.estado.requisitos)

					for (var i = 0; i < requisitos.length; i++) {
						if(tipos[i] == 'date'){
							var valor = {}
						}else{
							var valor = ''
						}
						let obj = {
							requisito: requisitos[i],
							tipo: tipos[i],
							valor: valor
						}
						this.form.requisitos.push(obj)
					}
				})
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
			if(!this.form.estado_posterior){
				this.form.estado_posterior = 0
			}
			axios.post(laroute.route('api.transacciones'), this.form)
				.then((res) => {
					if(res.data.saved){
						toastr.success('La transacción se ha realizado con éxito.')
						this.tarea.estado = false
						this.$emit('updated')
						return
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
				axios.get(laroute.route('api.estado', {id: id}))
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
			this.getPosteriores()
			return true
		},
		estadoPosterior(){
			return this.posteriores.filter((estado) => estado.id === this.form.estado_posterior)[0].nombre
		}
	}
}

</script>
