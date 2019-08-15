import Vue from 'vue'
import axios from 'axios'
import moment from 'moment'
import { getMarcas } from './../helpers/api'
import toastr from 'toastr'
import VueDatepicker from 'vue-datepicker'
import { TableComponent, TableColumn } from 'vue-table-component'

Vue.directive("material-select-1", {
  bind: function(el, binding, vnode) {
    $(function() {
    $(el).material_select();
    });
    var arg = binding.arg;
    if (!arg) arg = "change";
    arg = "on" + arg;
    el[arg] = function() {
    vnode.context.$data.columnas.col_1 = el.value;
    };
  },
  unbind: function(el) {
    $(el).material_select("destroy");
  }
});

Vue.directive("material-select-2", {
  bind: function(el, binding, vnode) {
    $(function() {
    $(el).material_select();
    });
    var arg = binding.arg;
    if (!arg) arg = "change";
    arg = "on" + arg;
    el[arg] = function() {
    vnode.context.$data.columnas.col_2 = el.value;
    };
  },
  unbind: function(el) {
    $(el).material_select("destroy");
  }
});

Vue.directive("material-select-3", {
  bind: function(el, binding, vnode) {
    $(function() {
    $(el).material_select();
    });
    var arg = binding.arg;
    if (!arg) arg = "change";
    arg = "on" + arg;
    el[arg] = function() {
    vnode.context.$data.columnas.col_3 = el.value;
    };
  },
  unbind: function(el) {
    $(el).material_select("destroy");
  }
});

const app = new Vue({
  el: '#app',
  components: { VueDatepicker, TableColumn, TableComponent },
  data: {
    option: {
      type: 'day',
      week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
      month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      format: 'YYYY-MM-DD'
    },
    marcas: [],
    errors: [],
    user_id: storage.user_id,
    search: '',
    form: {
      csrf_token: storage.csrf_token,
      user_id: storage.user_id,
      nombre: '',
      codigo: '',
      signo_distintivo: '',
      solicitante: '',
      clase: '',
      nro_inscripcion: '',
      nro_registro: '',
      fecha_vencimiento: {
        time: ''
      },
      distincion_producto_servicio: '',
      lema_comercial: ''
    },
    option: {
      type: 'day',
      week: ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
      month: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      format: 'YYYY-MM-DD'
    },
    pagination: {},
    path: '',
    columnas: {
      col_1: 'nombre',
      valor_1: '',
      col_2: 'nombre',
      valor_2: '',
      col_3: 'fecha_vencimiento',
      fecha_vencimiento_desde: {
        time: ''
      },
      fecha_vencimiento_hasta: {
        time: ''
      }
    },
    campo_marca_1:  {
      nombre: 'Nombre',
      codigo: 'Código',
      solicitante: 'Solicitante',
      clase: 'Clase',
      nro_incripcion: '# Inscripción',
      nro_registro: '# Registro',
      distincion_producto_servicio: 'Distinción Prod/Serv',
      lema_comercial: 'Lema Comercial'
    },
    campo_marca_2: {
      nombre: 'Nombre',
      codigo: 'Código',
      solicitante: 'Solicitante',
      clase: 'Clase',
      nro_incripcion: '# Inscripción',
      nro_registro: '# Registro',
      distincion_producto_servicio: 'Distinción Prod/Serv',
      lema_comercial: 'Lema Comercial'
    },
    campo_fecha: {
      fecha_vencimiento: 'Fecha de vencimiento',
      created_at: 'Fecha de creación'
    },
    moment(){
      return moment().format('YYYY-MM-DD')
    }
  },
  created() {
    this.fetchData()
    setTimeout(() => $('select').material_select(), 2000)
  },
  filters: {
    moment(date){
      return moment(date).locale('es').fromNow()
    },
    filterDate(date){
      return moment(date).format('YYYY-MM-DD')
    }
  },
  methods: {
    fetchData(){
      let url = this.laroute('home') + 'api/paginate/marcas'
      this.path = url
      axios.get(url)
        .then((res) => {
          this.pagination = res.data.marcas
          this.marcas = res.data.marcas.data
        })
    },
    searchData(){
      var url = this.laroute('home') + 'api/search/marcas?'
      url = url + 'col_1=' + this.columnas.col_1 + '&valor_1=' + this.columnas.valor_1
      url = url + '&'
      url = url + 'col_2=' + this.columnas.col_2 + '&valor_2=' + this.columnas.valor_2
      url = url + '&'
      url = url + 'col_3=' + this.columnas.col_3 + '&fecha_start=' + this.columnas.fecha_vencimiento_desde.time + '&fecha_end=' + this.columnas.fecha_vencimiento_hasta.time
      axios.get(url)
        .then((res) => {
          this.pagination = res.data.marcas
          this.marcas = res.data.marcas.data
        })
        this.path = url
    },
    imageChanged(event){
      this.form.signo_distintivo = ''
      this.showImage(event.target)
      var fileReader = new FileReader()
      fileReader.readAsDataURL(event.target.files[0])
      fileReader.onload = (event) => {
        this.form.signo_distintivo = event.target.result
      }
    },
    showImage(input){
      var output = document.getElementById('output');
      output.src = ''
      var reader = new FileReader()
      reader.onload = function(){
        var dataURL = reader.result
        output.src = dataURL
      }
      reader.readAsDataURL(input.files[0])
    },
    statusClass(estado){
      if(estado === 1){
        return {
          red: true
        }
      }
      if(estado === 2 || estado === 4){
        return {
          yellow: true
        }
      }
      return {
        green: true
      }
    },
    submit(){
      axios.post(laroute.route('marcas.index'), this.form)
        .then((res) => {
          if(res.data.saved){
            this.marcas.push(res.data.marca)
            $('#marcaModal').modal('close')
            toastr.success('La marca se ha guardado con éxito!')
            this.nombre = ''
            this.codigo = ''
            this.signo_distintivo = ''
            this.solicitante = ''
            this.clase = ''
            this.nro_inscripcion = ''
            this.nro_registro = ''
            this.fecha_vencimiento = ''
            this.distincion_producto_servicio = ''
            this.lema_comercial = ''
            this.getData()
          }
        })
        .catch((error) => {
          this.errors = error.response.data.errors
        })
    },
    next(){
      var url = this.path
      if(url.includes('?')){
         url = url + '&page=' + (this.pagination.current_page + 1)
      }else{
         url = url + '?page=' + (this.pagination.current_page + 1)
      }
      axios.get(url)
        .then((res) => {
          this.marcas = res.data.marcas.data
          this.pagination = res.data.marcas
        })
    },
    prev(){
      var url = this.path
      if(url.includes('?')){
         url = url + '&page=' + (this.pagination.current_page - 1)
      }else{
         url = url + '?page=' + (this.pagination.current_page - 1)
      }
      axios.get(url)
        .then((res) => {
          this.marcas = res.data.marcas.data
          this.pagination = res.data.marcas
        })
    },
    removeFecha(){
      this.columnas.fecha_vencimiento_desde.time = ''
      this.columnas.fecha_vencimiento_hasta.time = ''
      this.columnas.valor_1 = ''
      this.columnas.valor_2 = ''
      this.fetchData()
    },
    laroute(route, params){
      if(params){
        return laroute.route(route, params)
      }
      return laroute.route(route)
    }
  },
  computed: {
    checkPrev() {
      if(this.pagination.current_page === 1){
        return {
          disabled: true
        }
      }
    },
    checkNext() {
      if(this.pagination.current_page === this.pagination.last_page){
        return {
          disabled: true
        }
      }
    }
  }
})
