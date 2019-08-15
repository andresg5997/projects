@extends('layout.main')
@section('title', 'Clientes')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/table-component.css') }}">
<style type="text/css">
  .hidden{
    display: none;
  }
  .table-component{
    margin:0!important;
  }
  .btn:hover{
    background-color: rgba(33, 150, 243, 0.5);
  }
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEwBlk3mLG7HUGGXAqQkUHUs3_jazWdl4&libraries=places"></script>
@endsection

@section('content')
<div id="app">
  {{-- Modal --}}
  <div id="marcaModal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Crear nuevo cliente</h4>
      <form @submit.prevent="" enctype="multipart/form-data">
        <div class="row">
          <div class="input-field col m6 s12">
            <input id="nombre" type="text" v-model="form.nombre">
            <label for="nombre">Nombre</label>
            <small class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
          </div>
          <div class="input-field col m6 s12">
            <input id="apellido" type="text" v-model="form.apellido">
            <label for="apellido">Apellido</label>
            <small class="red-text" v-if="errors.apellido">@{{ errors.apellido[0] }}</small>
          </div>
          <div class="input-field col m6 s12">
            <input id="email" type="text" v-model="form.email">
            <label for="email">Correo Electrónico</label>
            <small class="red-text" v-if="errors.email">@{{ errors.email[0] }}</small>
          </div>
          <div class="input-field col m6 s12">
            <input id="nro_identificacion" type="text" v-model="form.nro_identificacion">
            <label for="nro_identificacion">Número de identificación</label>
          </div>
          <div class="input-field col m6 s12">
            <input type="text" id="telefono" v-model="form.telefono">
            <label for="telefono">Teléfono</label>
          </div>
          <template v-for="(dato, index) in form.datosAdicionales">
            <div class="input-field col m6 s12" v-if="dato.categoria == 'telefono'">
              <label for="telefono">Teléfono <b>(@{{dato.nombre}})</b></label>
              <input type="text" id="telefono" v-model="dato.valor">
            </div>
          </template>
          <div class="input-field col m6 s12">
            <label>Dirección</label>
            {{-- <input type="text" id="googlePlaces" class="form-control"> --}}
            <input {{-- id="googlePlaces" --}} type="text" v-model="form.direccion">
          </div>
          <template v-for="(dato, index) in form.datosAdicionales">
            <div class="input-field col m6 s12" v-if="dato.categoria == 'direccion'">
              <input {{-- id="googlePlaces" --}} type="text" v-model="dato.valor">
              <label>Dirección <b>(@{{dato.nombre}})</b></label>
            </div>
          </template>
          <div class="input-field col m6 s12">
            <input id="pais" type="text" v-model="form.pais">
            <label for="pais">País</label>
          </div>
          <div class="input-field col m6 s12">
            <input id="ciudad" type="text" v-model="form.ciudad">
            <label for="ciudad">Ciudad</label>
            <small class="red-text" v-if="errors.ciudad">@{{ errors.ciudad[0] }}</small>
          </div>
          <template v-for="(dato, index) in form.datosAdicionales">
            <div class="input-field col m6 s12" v-if="dato.categoria == 'otro'">
              <label for="dale"><b>@{{dato.nombre}}</b></label>
              <input id="dale" type="text" v-model="dato.valor">
            </div>
          </template>
          <div class="col m6 s12">
            <label for="fecha_nacimiento">Fecha de nacimiento</label><br>
            <vue-datepicker placeholder="Fecha de nacimiento" :date="form.fecha_nacimiento" :option="option"></vue-datepicker>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
      <button class="btn blue waves-effect" @click="submit">Guardar</button>
    </div>
  </div>
  {{-- End Modal --}}
  <div class="container">
    <br>
      <div class="card z-depth-1">
        <div class="card-image">
          <img src="/img/sample-1.jpg" height="100px">
          <span class="card-title">Clientes</span>
          <a href="#marcaModal" class="btn-floating halfway-fab waves-effect waves-light blue btn-large modal-trigger"><i class="material-icons">add</i></a>
        </div>
        <div class="card-content">
          <div class="row">
            <div class="col s4">
              <div class="input-field">
                <select v-material-select-1 class="icons">
                  <option v-for="(campo, index) in campo_marca_1" :value="index">@{{ campo }}</option>
                </select>
                <form @submit.prevent="searchData">
                  <input type="text" v-model="columnas.valor_1" placeholder="Buscar" @change="searchData">
                </form>
              </div>
            </div>
            <div class="col s4">
              <div class="input-field">
                <select v-material-select-2 class="icons">
                  <option v-for="(campo, index) in campo_marca_2" :value="index">@{{ campo }}</option>
                </select>
                <form @submit.prevent="searchData">
                  <input type="text" v-model="columnas.valor_2" placeholder="Buscar" @change="searchData">
                </form>
              </div>
            </div>
            <div class="col s4">
              <div class="input-field">
                <select v-material-select-3 class="icons">
                  <option v-for="(campo, index) in campo_fecha" :value="index">@{{ campo }}</option>
                </select>
                <div class="row">
                  <div class="col s6">
                    {{-- Date From --}}
                    <vue-datepicker
                    :date="columnas.fecha_vencimiento_desde"
                    :option="option"
                    @change="searchData"></vue-datepicker>
                    <p>Inicial</p>
                    <button class="btn blue hoverable waves-effect waves-light" @click="removeFecha">Reset</button>
                  </div>
                  <div class="col s6">
                    {{-- Date To --}}
                    <vue-datepicker
                    :date="columnas.fecha_vencimiento_hasta"
                    :option="option"
                    @change="searchData"></vue-datepicker>
                    <p>Final</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col s12">
              <button @click="prev()" class="btn btn-flat" :class="checkPrev">Anterior</button>
              <button @click="next()" class="btn btn-flat" :class="checkNext">Siguiente</button>
              <span>@{{ pagination.current_page }} / @{{ pagination.last_page }}</span>
  <table-component
     :data="marcas"
     sort-by="id"
     sort-order="asc"
     filter-placeholder="Buscar..."
     filter-no-results="No hay resultados."
     filter-input-class="hidden"
     >
     <table-column show="nombre" label="Nombre">
       <template scope="row">
         <a :href="laroute('marcas.show', {marca: row.id} )">@{{row.nombre}}</a>
       </template>
     </table-column>
     {{-- <table-column show="apellido" label="Apellido"> --}}
       {{-- <template scope="row">
        <template v-if="row.apellido">
          @{{ row.apellido }}
        </template>
        <template v-else>
          Sin apellido
        </template>
       </template> --}}
     {{-- </table-column> --}}
     {{-- <table-column show="clase" label="Clase"></table-column> --}}
     <table-column show="apellido" label="Apellido">
       <template scope="row">
        <template v-if="row.apellido">
          @{{ row.apellido }}
        </template>
        <template v-else>
          Sin apellido
        </template>
       </template>
     </table-column>
     <table-column show="email" label="Email">
       <template scope="row">
        <template v-if="row.email">
          @{{ row.email }}
        </template>
        <template v-else>
          Sin Email
        </template>
       </template>
     </table-column>
     <table-column show="ciudad" label="Ciudad">
       <template scope="row">
        <template v-if="row.ciudad">
          @{{ row.ciudad }}
        </template>
        <template v-else>
          Sin ciudad
        </template>
       </template>
     </table-column>
     <table-column show="pais" label="País">
       <template scope="row">
        <template v-if="row.pais">
          @{{ row.pais }}
        </template>
        <template v-else>
          Sin país
        </template>
       </template>
     </table-column>
     <table-column show="nro_identificacion" label="Nro Identificación">
       <template scope="row">
        <template v-if="row.nro_identificacion">
          @{{ row.nro_identificacion }}
        </template>
        <template v-else>
          Sin #
        </template>
       </template>
     </table-column>
     <table-column show="direccion" label="Dirección">
       <template scope="row">
        <template v-if="row.direccion">
          @{{ row.direccion }}
        </template>
        <template v-else>
          Sin dirección
        </template>
       </template>
     </table-column>
     <table-column show="fecha_nacimiento" label="Fecha Nac" data-type="date:DD/MM/YYYY">
       <template scope="row">
        <template v-if="row.fecha_nacimiento">
          @{{ row.fecha_nacimiento | filterDate }}
        </template>
        <template v-else>
          Sin fecha
        </template>
       </template>
     </table-column>
 </table-component>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var storage = {
    user_id: {{ Auth::id() }},
    csrf_token: '{{ csrf_token() }}'
  }
</script>
<script type="text/javascript" src="{{ asset('js/marcas/index.js') }}"></script>
@endsection
