import Vue from 'vue'
import axios from 'axios'
import Multiselect from 'vue-multiselect'
import toastr from 'toastr'
import swal from 'sweetalert2'

const app = new Vue({
	el: '#app',
	components: {
		Multiselect
	},
	data: {
		dashboards: storage.dashboards,
		procesos: storage.procesos,
		estados: storage.estados,
		procesoEstados: [],
		procesoEstadosEdit: [],
		selectedProceso: {},
		selectedProcesoEdit: {},
		showEstados: 0,
		selectedEstadoEdit: {},
		editIndex: 0,
		form: {
			csrf_token: storage.csrf_token,
			estado_id: '',
			dias_estado: 0
		}
	},
	methods: {
		closeNew(){
			$('#dashboardModal').modal('close')
		},
		closeEdit(){
			$('#dashboardEditModal').modal('close')
		},
		botonEdit(index){
			this.procesoEstadosEdit = []
			this.editIndex = index
			this.form.estado_id = this.dashboards[index].estado_id
			this.form.dias_estado = this.dashboards[index].dias_estado
			this.estados.forEach((estado) => {
				if(estado.id == this.form.estado_id){
					this.selectedEstadoEdit = estado
					this.procesos.forEach((proceso) => {
						if(estado.proceso_id == proceso.id){
							this.selectedProcesoEdit = proceso
						}
					})
				}
			})
			this.estados.forEach((estado) => {
				if(estado.proceso_id == this.selectedProcesoEdit.id){
					this.procesoEstadosEdit.push(estado)
				}
			})
			$('#dashboardEditModal').modal('open')
		},
		botonAdd(){
			if(this.dashboards.length == 4){
				toastr.error('No pueden haber más de 4 paneles.')
	        }else{
	        	this.form = {
					estado_id: '',
					dias_estado: 0
				}
	            $('#dashboardModal').modal('open')
	        }
		},
		selectProceso(event){
			this.procesoEstados = []
			this.selectedProceso = event.id
			this.showEstados = 1
			this.estados.forEach((estado) => {
				if(estado.proceso_id == event.id){
					this.procesoEstados.push(estado)
				}
			})
		},
		selectEstado(event){
			this.form.estado_id = event.id
		},
		editProceso(event){
			this.procesoEstadosEdit = []
			this.selectedProcesoEdit = event.id
			this.estados.forEach((estado) => {
				if(estado.proceso_id == event.id){
					this.procesoEstadosEdit.push(estado)
				}
			})
		},
		quitarPanel(index){
            swal({
                title: '¿Estas seguro?',
                text: "¡No podrás revertir esto!",
                type: 'warning',
                showCancelButton: true,
            })
                .then(() => {
					axios.delete(laroute.route('dashboard.destroy', {dashboard: this.dashboards[index].id}))
						.then((res) => {
							if(res.data.saved){
								toastr.info('El panel se ha eliminado.')
								this.dashboards.splice(index, 1)
							}
						})
				})
		},
		editPanel(){
			this.form.dias_estado = parseInt(this.form.dias_estado)
			axios.put(laroute.route('dashboard.update', {dashboard: this.dashboards[this.editIndex].id}), this.form)
				.then((res) => {
					if(res.data.updated){
	            		$('#dashboardEditModal').modal('close')
	            		toastr.success('¡El panel fue editado con éxito!')
	            		this.dashboards[this.editIndex] = res.data.dashboard
						this.form.estado = ''
						this.form.dias_estado = 0
					}
				})
		},
		submit(){
			this.form.dias_estado = parseInt(this.form.dias_estado)
			console.log(this.form)
			axios.post(laroute.route('dashboard.store'), this.form)
				.then((res) => {
					if(res.data.saved){
						toastr.success('¡El panel ha sido agregado con éxito!')
			            $('#dashboardModal').modal('close')
						var dashboardTemporal = res.data.dashboard
						this.estados.forEach((estado) => {
							if(this.form.estado_id == estado.id){
								dashboardTemporal.estado = estado
							}
						})
						console.log(dashboardTemporal)
			            this.dashboards.push(dashboardTemporal)
						this.procesoEstados = []
						this.selectedProceso = ''
						this.form.estado = ''
						this.form.dias_estado = 0
					}
				})
		}
	}
})
