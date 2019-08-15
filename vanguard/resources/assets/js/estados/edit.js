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
            proceso: '',
            proceso_id: storage.proceso_id,
            estado_posterior: [],
            requisitos: [
                { nombre: '', tipo: '', opciones: [] , index: ''}
            ],
            tiempo_seguimiento: '',
            titulo_tarea: '',
            tareas: [
                { titulo: '' }
            ]
        },
        tipos: [
            {nombre: 'Texto', tipo: 'text', index: 0},
            {nombre: 'Fecha', tipo: 'date', index: 1},
            {nombre: 'Archivo', tipo: 'file', index: 2},
            {nombre: 'Seleccion', tipo: 'select', index: 3},
            {nombre: 'Mapa', tipo: 'map', index: 4},
            {nombre: 'Automatizada', tipo: 'auto', index: 5},
            {nombre: 'Campo de cliente', tipo: 'cliente', index: 6}
        ],
        tipos_campo_cliente: [
            {nombre: 'Nombre', tipo: 'nombre'},
            {nombre: 'Apellido', tipo: 'apellido'},
            {nombre: 'Correo Electrónico', tipo: 'email'},
            {nombre: 'Ciudad', tipo: 'ciudad'},
            {nombre: 'País', tipo: 'pais'},
            {nombre: 'Dirección', tipo: 'direccion'}
        ],
        tipos_auto: [
            {nombre: 'Correo Electronico', tipo: 'email', tipo_tarea: '', tipo_fecha: '', fecha: ''},
            {nombre: 'Mensaje de Texto', tipo: 'sms', tipo_tarea: '', tipo_fecha: '', fecha: ''}
        ],
        tipo_fecha_auto: [
            {nombre: 'Al hacer la tarea', tipo: 'tarea'},
            {nombre: 'Seleccionar tiempo', tipo: 'fecha'}
        ],
        tipo_tarea_email: [],
        tipo_tarea_sms: [],
        procesos: [],
        estados: [],
        estado_anterior: {},
        errors: {},
        estado: {},
        showEstados: 0,
        prueba: {}
    },
    created(){
        this.getData()
        this.getEstado()
    },
    methods: {
        agregarRequisito(){
            this.form.requisitos.push({
                nombre: '',
                tipo: '',
                opciones: []
            })
        },
        agregarOpcion(index){
            this.form.requisitos[index].opciones.push({
                nombre: ''
            })
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
        quitarOpcion(index, indexOpc){
            this.form.requisitos[index].opciones.splice(indexOpc, 1)
        },
        store(){
            axios.put(laroute.route('estados.update', {estado: this.id}), this.form)
                .then((res) => {
                    if(res.data.updated){
                        swal('El estado se ha actualizado con éxito!', '', 'success')
                        setTimeout(() => {
                            window.location = laroute.route('procesos.show', {proceso: this.form.proceso_id})
                        }, 1500)
                        return
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        getEstados(){
            this.estados = []
            axios.get(laroute.route('api.estados'))
                .then((res) => {
                    res.data.estados.forEach((estado) => {
                        if(this.form.proceso.id == estado.proceso_id){
                            let obj = {
                                nombre: estado.nombre,
                                id: estado.id
                            }
                            this.estados.push(obj)
                        }
                    })
                })
        },
        getData(){
            axios.get(laroute.route('api.procesos'))
                .then((res) => {
                    this.procesos = res.data.procesos
                })
            axios.get(laroute.route('api.correos'))
                .then((res) => {
                    res.data.correos.forEach((correo) => {
                        let obj = {
                            id: correo.id,
                            correo_destino: correo.correo_destino,
                            id_plantilla: correo.id_plantilla
                        }
                        this.tipo_tarea_email.push(obj)
                    })
                })
            axios.get(laroute.route('api.estados'))
                .then((res) => {
                    res.data.estados.forEach((estado) => {
                        if(this.form.proceso_id == estado.proceso_id){
                            let obj = {
                                nombre: estado.nombre,
                                id: estado.id
                            }
                            this.estados.push(obj)
                        }
                    })
                })
        },
        getEstado(){
            axios.get(laroute.route('estado.get', {estado: this.id}))
                .then((res) => {
                    this.estado = res.data.estado
                    this.estado_anterior = res.data.estado_anterior
                    this.form = res.data.estado
                    this.form.estado_posterior = this.parseEstados(storage.estado_posterior)
                    this.form.requisitos = this.parseRequisitos(this.estado.requisitos)
                    this.form.proceso = this.procesos.filter((proceso) => proceso.id === parseInt(this.form.proceso_id))[0]
                })
        },
        cambiarTipo(index, event){
            this.form.requisitos[index].tipo = event.tipo
            this.form.requisitos[index].index = event.index
        },
        selectFecha(index, event){
            this.form.requisitos[index].opciones.tipo_fecha = event.tipo
        },
        selectCorreo(index, event){
            let obj = {
                id: event.id,
                correo_destino: event.correo_destino
            }
            this.form.requisitos[index].opciones.tipo_tarea = obj
        },
        parseEstados(ids){
            let idsArray = ids.split(',')
            let results = []
            idsArray.forEach((id) => {
                this.estados.forEach((estado) => {
                    if(estado.id == id){
                        let obj = estado
                        if(obj != undefined) {
                            results.push(obj)
                        }
                    }
                })
            })
            return results
        },
        parseRequisitos(requisitos){
            let requisito = Object.values(requisitos)
            let parsed = []
            requisito.forEach((valor, index) => {
                let obj = {}
                if(valor.tipo == 'auto') {
                    let email = {}
                    if(Number.isInteger(valor.opciones.tipo_tarea)){
                        email = this.tipo_tarea_email.filter((tarea_email) => tarea_email.id === parseInt(valor.opciones.tipo_tarea))[0]
                    }
                    else {
                        email = {
                            id: this.tipo_tarea_email[0].id,
                            correo_destino: 'error al traer los datos del correo',
                            id_plantilla: 'error al traer los datos del correo'
                        }
                    }
                    let fecha = {}
                    if (typeof valor.opciones.tipo_fecha !== 'undefined') {
                        fecha = this.tipo_fecha_auto.filter((fecha_auto) => fecha_auto.tipo === valor.opciones.tipo_fecha)[0]
                    } else {
                        fecha = {
                            nombre: 'error al traer los datos del correo',
                            tipo: 'tarea'
                        }
                    }
                    if (typeof valor.opciones.fecha == 'undefined') {
                        valor.opciones.fecha = ''
                    }
                    obj = {
                        nombre: valor.nombre,
                        tipo: valor.tipo,
                        opciones: {
                            fecha: valor.opciones.fecha,
                            nombre: valor.opciones.nombre,
                            tipo: valor.opciones.tipo,
                            tipo_fecha: fecha,
                            tipo_tarea: email
                        },
                        index: valor.index
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
        }
    }
})
