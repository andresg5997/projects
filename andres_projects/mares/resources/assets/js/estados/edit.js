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
        id: storage.estado_id,
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
        estado_anterior: {},
        errors: {},
        estado: {}
    },
    created(){
        this.getData()
        this.getEstado()
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
            axios.put(laroute.route('estados.update', {id: this.id}), this.form)
                .then((res) => {
                    if(res.data.updated){
                        swal('El estado se ha actualizado con éxito!', '', 'success')
                        setTimeout(() => {
                            // window.location = '/estados'
                            window.location = laroute.route('estados.index')
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
        getEstado(){
            axios.get(laroute.route('api.estado', {id: this.id}))
                .then((res) => {
                    console.log(res.data.estado)
                    this.estado = res.data.estado
                    this.estado_anterior = res.data.estado_anterior
                    this.form = res.data.estado
                    this.form.estado_posterior = this.parseEstados(this.estado.estado_posterior)
                    this.form.requisitos = this.parseRequisitos(this.estado.requisitos)
                })
        },
        cambiarTipo(index, event){
            this.form.requisitos[index].tipo = event.tipo
        },
        parseEstados(ids){
            let idsArray = ids.split(',')
            let results = []
            idsArray.forEach((id) => {
                results.push(this.estados.filter((estado) => estado.id === parseInt(id))[0])
            })
            return results
        },
        parseRequisitos(requisitos){
            let nombres = Object.keys(requisitos)
            let tipos = Object.values(requisitos)
            let parsed = []
            nombres.forEach((nombre, index) => {
                let obj = {
                    nombre: nombre,
                    tipo: tipos[index]
                }
                parsed.push(obj)
            })
            return parsed
        }
    }
})
