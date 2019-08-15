import Vue from 'vue'
import axios from 'axios'
import swal from 'sweetalert2'

new Vue({
	el: '#app',
	data: {
		proceso: {
            nombre: '',
            descripcion: ''
        },
        procesoEstados: [],
		form: {
			nombre: '',
			descripcion: '',
            csrf_token: storage.csrf_token
		},
        proceso_id: storage.proceso_id
	},
	created(){
		this.getData()
	},
	methods: {
        getData(){
            axios.get(laroute.route('api.proceso', {id: this.proceso_id}))
                .then((res) => {
                    this.proceso = res.data.proceso
                })
            axios.get(laroute.route('api.estados'))
                .then((res) => {
                    res.data.estados.forEach((estado) =>{
                        if (estado.proceso_id == this.proceso_id) {
                            this.procesoEstados.push(estado)
                        }
                    })
                })
        },
        deleteEstado(id, index){
            swal({
                title: '¿Estas seguro?',
                text: "¡No podrás revertir esto!",
                type: 'warning',
                showCancelButton: true,
            })
                .then(() => {
                    axios.delete(laroute.route('estados.destroy', {estado: id}))
                        .then((res) => {
                            if(res.data.deleted){
                                swal('Hecho!', 'El estado ha sido eliminado.', 'success')
                                this.procesoEstados.splice(index, 1)
                            }
                        })
                })
        },
        laroute(route, params){
            if(params){
                return laroute.route(route, params)
            }
            return laroute.route(route)
        }
	}
})