import Vue from 'vue'
import axios from 'axios'
import moment from 'moment'
import { getMarcas } from './../helpers/api'
import toastr from 'toastr'
import VueDatepicker from 'vue-datepicker'
import { TableComponent, TableColumn } from 'vue-table-component'
// import * as VueGoogleMaps from 'vue2-google-maps'

// Vue.use(VueGoogleMaps, {
//   load: {
//     key: 'AIzaSyAATw7zkkc8lZjaBjtsv-7zUYJR1CRv0_w',
//     libraries: 'places'
//   }
// })

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
    googleAddress: {
        street_number: null,
        street_name: null,
        city: null,
        state: null,
        zipcode: null,
        country: null,
        url: null,
        autocomplete: null
    },
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
      apellido: '',
      email: '',
      ciudad: '',
      pais: '',
      telefono: '',
      nro_identificacion: '',
      direccion: '',
      fecha_nacimiento: {
        time: ''
      },
      datosAdicionales: []
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
      apellido: 'Apellido',
      email: 'Correo Electrónico',
      ciudad: 'Ciudad',
      pais: 'País',
      nro_identificacion: '# Identificación',
      direccion: 'Dirección'
    },
    campo_marca_2: {
      nombre: 'Nombre',
      apellido: 'Apellido',
      email: 'Correo Electrónico',
      ciudad: 'Ciudad',
      pais: 'País',
      nro_identificacion: '# Identificación',
      direccion: 'Dirección'
    },
    campo_fecha: {
      fecha_nacimiento: 'Fecha de nacimiento',
      created_at: 'Fecha de creación'
    }
  },
  created() {
    this.fetchData()
    this.getConfigData()
    setTimeout(() => $('select').material_select(), 2000)
  },
  mounted() {
    var input = document.getElementById('googlePlaces')
    var options = {
        types: ['geocode']
    }
    this.googleAddress.autocomplete = new google.maps.places.Autocomplete(input, options);
    this.googleAddress.autocomplete.addListener('place_changed', this.getAddress);
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
              this.form.datosAdicionales.push(obj)
            }
          })
        })
    },
    initMap() {
      var input = document.getElementById('googlePlaces');
      var autocomplete = new google.maps.places.Autocomplete(input);
      var infowindow = new google.maps.InfoWindow();
      autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          // User entered the name of a Place that was not suggested and
          // pressed the Enter key, or the Place Details request failed.
          window.alert("No details available for input: '" + place.name + "'");
          return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
          map.setZoom(17);  // Why 17? Because it looks good.
        }
        marker.setIcon(/** @type {google.maps.Icon} */({
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(' ');
        }
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
      });
    },
    searchLocation() {
      var geocoder = new google.maps.Geocoder()
      geocoder.geocode({'address': this.searchInput}, (results, status) => {
        if (status === 'OK') {
          this.center = {
            lat: results[0].geometry.location.lat(),
            lng: results[0].geometry.location.lng()
          }
          this.markers[0].position = {
            lat: results[0].geometry.location.lat(),
            lng: results[0].geometry.location.lng()
          }
          this.location = results
        }
        console.log(this.location)
      })
    },
    getAddress(){
      var place = this.googleAddress.autocomplete.getPlace()
      console.log("place")
      console.log(place)
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        switch (addressType) {
          case 'street_number':
            this.googleAddress.street_number = place.address_components[i]['short_name'];
            break;
          case 'route':
            this.googleAddress.street_name = place.address_components[i]['short_name'];
            break;
          case 'locality':
            this.googleAddress.city = place.address_components[i]['long_name'];
            break;
          case 'administrative_area_level_1':
            this.googleAddress.state = place.address_components[i]['short_name'];
            break;
          case 'postal_code':
            this.googleAddress.zipcode = place.address_components[i]['short_name'];
            break;
          case 'country':
            this.googleAddress.country = place.address_components[i]['short_name'];
            break;
        }
      }
    },
    changeMapIndex(index){
      this.mapIndex = index
    },
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
      axios.post(laroute.route('marcas.store'), this.form)
        .then((res) => {
          if(res.data.saved){
            this.marcas.push(res.data.marca)
            $('#marcaModal').modal('close')
            toastr.success('El cliente se ha guardado con éxito!')
            setTimeout(() => {
              window.location = laroute.route('marcas.show', {marca: res.data.marca.id})
            }, 1000)
            this.form.nombre = ''
            this.form.apellido = ''
            this.form.email = ''
            this.form.ciudad = ''
            this.form.telefono = ''
            this.form.pais = ''
            this.form.nro_identificacion = ''
            this.form.direccion = ''
            this.form.fecha_nacimiento = {
              time:''
            }
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
