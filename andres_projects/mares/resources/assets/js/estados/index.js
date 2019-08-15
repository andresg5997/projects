import Vue from 'vue'
import axios from 'axios'
import toastr from 'toastr'
import swal from 'sweetalert2'

new Vue({
    el: '#app',
    data: {
        estados: [],
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
        },
        deleteEstado(id, index){
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
            })
                .then(() => {
                    axios.delete(laroute.route('api.estado', {id: id}))
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
