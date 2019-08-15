import Vue from 'vue'
import axios from 'axios'
import Multiselect from 'vue-multiselect'

const app = new Vue({
    el: '#app',
    components: {
        Multiselect
    },
    data: {
        datosAdicionales: [],
        opciones: [
            {categoria: 'Teléfono', valor: 'telefono'},
            {categoria: 'Dirección', valor: 'direccion'},
            {categoria: 'Otro', valor: 'otro'}
        ],
        selectedCategorias: [],
        showConfirmacion: false,
        errors: [],
        error: false
    },
    created(){
        this.getData()
    },
    methods: {
        getData(){
            axios.get(laroute.route('marcas.datos.config'))
                .then((res) => {
                    res.data.datosAdicionales.forEach((dato) => {
                        if(dato.cliente_id == -1){
                            var obj = {
                                id: dato.id,
                                categoria: dato.categoria,
                                nombre: dato.nombre
                            }
                            var categoriaObj = {
                                categoria: this.humanize(dato.categoria)
                            }
                            this.datosAdicionales.push(obj)
                            this.selectedCategorias.push(categoriaObj)
                        }
                    })
                })
        },
        selectCategoria(event, index){
            this.datosAdicionales[index].categoria = event.valor
        },
        agregarDato(){
            var obj = {
                categoria: '',
                nombre: ''
            }
            this.datosAdicionales.push(obj)
        },
        borrarDato(index){
            this.selectedCategorias.splice(index, 1)
            this.datosAdicionales.splice(index, 1)
        },
        submit(){
            var index = 0
            this.errors = []
            this.error = false
            this.datosAdicionales.forEach((dato) => {
                if(!dato.categoria || !dato.nombre){
                    this.errors[index] = true
                    this.error = true
                }
                index++
            })
            if(!this.error){
                axios.post(laroute.route('marcas.datos.config.store'), this.datosAdicionales)
                    .then((res) => {
                        if(res.data.updated){
                            this.showConfirmacion = true
                            setTimeout(function(){
                                self.showConfirmacion = false
                            }, 4000)
                        }
                    })
            }
        },
        humanize(str){
            var frags = str.split('_')
            for (let i = 0; i < frags.length; i++) {
                frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1)
            }
            return frags.join(' ')
        },
    }
})
