import Vue from 'vue'
import axios from 'axios'
import toastr from 'toastr'

new Vue({
  el: '#app',
  data: {
    procesos: [],
    form: {
      nombre: '',
      descripcion: '',
      csrf_token: storage.csrf_token
    },
    errors: {}
  },
  created(){
    this.getData()
  },
  methods: {
    getData(){
      axios.get(laroute.route('api.procesos'))
        .then((res) => {
          this.procesos = res.data.procesos
        })
    },
    submit(){
      axios.post(laroute.route('api.procesos.store'), this.form)
        .then((res) => {
          if(res.data.saved){
            this.procesos.push(res.data.proceso)
            $('#procesoModal').modal('close')
            toastr.success('El proceso se ha guardado con éxito!')
          }
        })
        .catch((err) => {
          this.errors = err.response.data.errors
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
