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

const app = new Vue({
    el: '#app',
    components: { VueDatepicker },
    data: {
        errors: {},
        estado: {
            estado_posterior: '0'
        },
        form: {
            csrf_token: storage.csrf_token,
            user_id: storage.auth_id,
            tarea_id: storage.tarea_id,
            estado_id: 0,
            requisitos: [],
            asignar: 0,
            estado_posterior: 0,
            marca_id: 0,
            fecha_vencimiento: {}
        },
        usuarios: [],
        posteriores: [],
        tarea: {},
        option: {
            type: 'day',
            week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
            month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            format: 'YYYY-MM-DD'
        }
    },
    created(){
        this.getTarea()
        this.getUsuarios()
        setTimeout(() => $('select').material_select(), 1000)
    },
    methods: {
        getTarea(){
            axios.get(laroute.route('api.tarea', {id: storage.tarea_id}))
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
            axios.get(laroute.route('api.usuario.usuarios', {id: storage.auth_id}))
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
                        console.log('doesnt have multiple')
                        this.form.estado_posterior = parseInt(this.estado.estado_posterior)
                    } else {
                        let parts = this.estado.estado_posterior.split(',')
                        this.form.estado_posterior = parseInt(parts[0])
                    }


                    let requisitos = Object.keys(this.estado.requisitos)
                    let tipos = Object.values(this.estado.requisitos)

                    for (var i = 0; i < requisitos.length; i++) {
                        let obj = {
                            requisito: requisitos[i],
                            tipo: tipos[i],
                            valor: ''
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
            this.form.signo_distintivo = ''
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
        }
    }
})
