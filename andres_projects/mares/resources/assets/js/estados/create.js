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
            estado_posterior: [],
            requisitos: [
                { nombre: '', tipo: '' }
            ],
            tareas: [
                { titulo: '' }
            ],
        },
        tipos: [
            {nombre: 'Texto', tipo: 'text'},
            {nombre: 'Fecha', tipo: 'date'},
            {nombre: 'Archivo', tipo: 'file'}
        ],
        estados: [],
        errors: {}
    },
    created(){
        this.getData()
    },
    methods: {
        agregarRequisito(){
            this.form.requisitos.push({
                nombre: '',
                tipo: ''
            })
            // material_select()
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
        quitarTarea(index){
            this.form.tareas.splice(index, 1)
        },
        store(){
            axios.post(laroute.route('estados.store'), this.form)
                .then((res) => {
                    if(res.data.saved){
                        swal('El estado se ha guardado con éxito!', '', 'success')
                        setTimeout(() => {
                            window.location = laroute.route('estados.store')
                        }, 1500)
                        return
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        getData(){
            axios.get(laroute.route('api.estados'))
                .then((res) => {
                    res.data.estados.forEach((estado) => {
                        let obj = {
                            nombre: estado.nombre,
                            id: estado.id
                        }
                        this.estados.push(obj)
                    })
                })
        },
        cambiarTipo(index, event){
            this.form.requisitos[index].tipo = event.tipo
        }
    }
})
