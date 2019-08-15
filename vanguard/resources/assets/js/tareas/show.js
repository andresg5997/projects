import Vue from 'vue'
import axios from 'axios'
import toastr from 'toastr'
import moment from 'moment'
import TransaccionCreateComponent from './../components/TransaccionCreate.vue'

const app = new Vue({
    el: '#root',
    components: { TransaccionCreate: TransaccionCreateComponent },
    data: {
        edit: false,
        tarea: {},
        form: {
            titulo: '',
            descripcion: '',
            csrf_token: storage.csrf_token
        }
    },
    created(){
        this.getData()
    },
    methods: {
        getData(){
            axios.get(laroute.route('api.tarea', {id: storage.tarea_id}))
                .then((res) => {
                    this.tarea = res.data.tarea
                    this.form.titulo = this.tarea.titulo
                    this.form.descripcion = this.tarea.descripcion
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        changeStatus(tarea, event){
            if(tarea.status === '0'){
                this.updateTask(tarea, '1')
            }else{
                this.updateTask(tarea, '0')
            }
        },
        updateTask(tarea, value){
            axios.put(laroute.route('tareas.update', {tarea: tarea.id}), {status: value})
                .then((res) => {
                    this.tarea.status = res.data.tarea.status
                    toastr.success('La tarea se ha actualizado con éxito!')
                })
                .catch((error) => {
                    console.log(error.response.data)
                })
        },
        update() {
            axios.put(laroute.route('tareas.update', {tarea: this.tarea.id}), this.form)
                .then((res) => {
                    if(res.data.updated){
                        this.tarea.titulo = this.form.titulo
                        this.tarea.descripcion = this.form.descripcion
                        if(this.tarea.marca){
                            setTimeout(() => {
                                window.location = this.laroute('marcas.show', {marca: this.tarea.marca.id})
                            }, 1500)
                        }
                        this.edit = false
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        laroute(route, params){
            if(params){
                return laroute.route(route, params)
            }
            return laroute.route(route)
        }
    },
    filters: {
        moment(date){
            return moment(date).format('D-M-Y')
        },
        fromNow(date){
            return moment(date).locale('es').fromNow()
        },
        humanize(str){
            var frags = str.split('_')
            for (let i = 0; i < frags.length; i++) {
                frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1)
            }
            return frags.join(' ')
        }
    }
})
