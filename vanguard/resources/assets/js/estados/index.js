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
        estados: [],
        procesos: [{
            id: -1,
            nombre: 'Mostrar todos los estados'
        }],
        proceso_select: {
            id: -1
        },
        form: {
            csrf_token: storage.csrf_token
        }
    },
    created(){
        this.getData()
    },
    methods: {
        getData(){
            axios.get(laroute.route('api.estados'))
                .then((res) => {
                    this.estados = res.data.estados
                })
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
                                this.estados.splice(index, 1)
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
    },
    computed: {
        noPosteriores(){
            return this.estados.filter((estado) => estado.posteriores === undefined)
        }
    }
})
