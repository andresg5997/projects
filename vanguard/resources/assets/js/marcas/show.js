import Vue from 'vue'
import axios from 'axios'
import { getMarca } from './../helpers/api'
import moment from 'moment'
import Multiselect from 'vue-multiselect'
import VueDatepicker from 'vue-datepicker'

new Vue({
  el: '#app',
  components: { VueDatepicker, Multiselect },
  data: {
    archivos: {
      archivos: [],
      csrf_token: storage.csrf_token,
      user_id: storage.auth_id
    },
    option: {
      type: 'day',
      week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
      month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      format: 'YYYY-MM-DD'
    },
    procesos: [],
    showOtrosDatos: false,
    configDatosAdicionales: [],
    datosAdicionales: [],
    marca: {
      nombre: '',
      apellido: '',
      email: '',
      ciudad: '',
      pais: '',
      nro_identificacion: '',
      direccion: '',
      fecha_nacimiento: '',
      tareas: [],
      archivos: [],
      transacciones: [],
    },
    edit: false,
    errors: {},
    form: {
      nombre: '',
      apellido: '',
      email: '',
      telefono: '',
      ciudad: '',
      pais: '',
      nro_identificacion: '',
      direccion: '',
      fecha_nacimiento: {
        time: ''
      },
      datosAdicionales: [],
      csrf_token: storage.csrf_token
    },
    auth_id: storage.auth_id,
    type: storage.type,
    selectedProceso: {
      id: 0,
      estados: []
    }
  },
  created(){
    this.getData()
    this.getConfigData()
  },
  methods: {
    getData(){
      axios.get(laroute.route('api.marca', {id: storage.marca_id}))
        .then((res) => {
          this.marca = res.data.marca
          this.form.nombre = this.marca.nombre
          this.form.apellido = this.marca.apellido
          this.form.email = this.marca.email
          this.form.telefono = this.marca.telefono
          this.form.ciudad = this.marca.ciudad
          this.form.pais = this.marca.pais
          this.form.nro_identificacion = this.marca.nro_identificacion
          this.form.direccion = this.marca.direccion
          this.form.fecha_nacimiento = {time: this.marca.fecha_nacimiento}
        })
      axios.get(laroute.route('api.procesos'))
        .then((res) => {
          this.procesos = res.data.procesos
        })
    },
    getConfigData(){
      axios.get(laroute.route('marcas.datos.config'))
        .then((res) => {
          res.data.datosAdicionales.forEach((dato) => {
            if(dato.cliente_id == -1){ // Para validar que son los datos de configuración
              var obj = {
                categoria: dato.categoria,
                nombre: dato.nombre,
                valor: ''
              }
              this.configDatosAdicionales.push(obj)
              if(dato.categoria == 'otro'){
                this.showOtrosDatos = true
              }
            }
            if(dato.cliente_id == this.marca.id){
              var obj = {
                categoria: dato.categoria,
                nombre: dato.nombre,
                valor: dato.valor
              }
              this.datosAdicionales.push(obj)
            }
          })
          this.configDatosAdicionales.forEach((configDato) => {
            var obj = {
              categoria: configDato.categoria,
              nombre: configDato.nombre,
              valor: configDato.valor
            }
            this.datosAdicionales.forEach((dato) => {
              if((configDato.categoria == dato.categoria) && (configDato.nombre == dato.nombre)){
                obj.valor = dato.valor
              }
            })
            this.form.datosAdicionales.push(obj)
          })
        })
    },
    borrarCliente(){
      axios.delete(laroute.route('marcas.destroy', {marca: this.marca.id}))
      .then((res) => {
        if(res.data.deleted){
          console.log('Cliente Borrado')
          setTimeout(() => {
            window.location = laroute.route('marcas.index')
          }, 1500)
        }
      })
    },
    selectProceso(event){
      this.selectedProceso.id = event.id
      this.selectedProceso.estados = []
      event.estados.forEach((estado) => {
        if(estado.visible == '1'){
          this.selectedProceso.estados.push(estado)
        }
      })
    },
    iniciarProceso(){
      axios.post(laroute.route('procesos.init', {proceso: this.selectedProceso.id, marca: this.marca.id}))
        .then((res) => {
          if(res.data.saved){
            $('#procesoModal').modal('close')
            toastr.success('¡El proceso ha sido iniciado con éxito!')
          }
        })
    },
    editMarca(){
      axios.put(laroute.route('api.marca.update', {id: storage.marca_id}), this.form)
        .then((res) => {
          if(res.data.updated){
            this.edit = false
            this.marca.fecha_vencimiento = this.form.fecha_vencimiento['time']
            toastr.success('Los datos del cliente fueron actualizados.')
            this.getData()
          }
        }).catch((error) => {
          this.errors = error.response.data.errors
        })
    },
    showEdit(){
      this.edit = !this.edit
      if(this.edit){
        this.form.fecha_vencimiento = {
          'time': this.marca.fecha_vencimiento
        }
      }
    },
    laroute(route, params){
      if(params){
        return laroute.route(route, params)
      }
      return laroute.route(route)
    },
    filesChanged(e){
      this.archivos.archivos = []
      if(e.target.files.length > 0){
        for(let i = 0; i < e.target.files.length; i++){
          let reader = new FileReader()
          reader.readAsDataURL(e.target.files[i])
          reader.onload = () => {
            let archivo = {
              archivo: reader.result,
              titulo: e.target.files[i].name
            }
            this.archivos.archivos.push(archivo)
          }
        }
        return;
      }
    },
    submit() {
      axios.post(laroute.route('home') + 'api/marcas/' + this.marca.id + '/subirArchivos', this.archivos)
      .then((res) => {
        if(res.data.uploaded){
          toastr.success('Se subieron con éxito ' + res.data.total + ' archivos y ' + res.data.failed + ' dieron error.')
          this.archivos.archivos = []
          setTimeout(() => {
            window.location = this.laroute('marcas.show', {marca: this.marca.id})
          }, 2000)
        }
      })
    }
  },
  computed: {
    check(){
      if(this.type == 'admin'){
        return true
      }
      return false
    },
    archivosFiltrados(){
      return this.marca.archivos.filter((archivo) => archivo.usuario)
    }
  },
  filters: {
    moment(date){
      return moment(date).format('DD-MM-YYYY')
    },
    fromNow(date){
      return moment(date).locale('es').fromNow()
    },
    filter(date){
        return moment(date).format('DD-MM-YYYY')
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
