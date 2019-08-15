@extends('layout.main')
@section('title', 'Marcas')

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
@endsection

@section('content')
<div id="app">
  <div id="marcaModal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Crear nueva marca</h4>
      <form @submit.prevent="" enctype="multipart/form-data">
        <div class="row">
          <div class="col s12 m8">
            <div class="file-field input-field">
              <div class="btn blue">
                <span>Signo distintivo</span>
                <input type="file" accept="image/*" @change="imageChanged">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path" type="text">
              </div>
              <small class="red-text" v-if="errors.signo_distintivo">@{{ errors.signo_distintivo[0] }}</small>
            </div>
          </div>
          <div class="col s12 m4">
            <img src="" alt="" id="output" class="responsive-img">
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6">
            <input id="nombre" type="text" v-model="form.nombre">
            <label for="nombre">Nombre</label>
            <small class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
          </div>
          <div class="input-field col s6">
            <input id="codigo" type="text" v-model="form.codigo">
            <label for="codigo">Código</label>
            <small class="red-text" v-if="errors.código">@{{ errors.código[0] }}</small>
          </div>
        </div>
        <div class="row">
          <div class="input-field col m6 s12">
            <input id="solicitante" type="text" v-model="form.solicitante">
            <label for="solicitante">Solicitante</label>
            <small class="red-text" v-if="errors.solicitante">@{{ errors.solicitante[0] }}</small>
          </div>
          <div class="input-field col m6 s12">
            <input id="clase" type="text" v-model="form.clase">
            <label for="clase">Clase</label>
            <small class="red-text" v-if="errors.clase">@{{ errors.clase[0] }}</small>
          </div>
        </div>
        <div class="row">
          <div class="input-field col m6 s12">
            <input id="nro_inscripcion" type="text" v-model="form.nro_inscripcion">
            <label for="nro_inscripcion">Número de inscripción</label>
          </div>
          <div class="input-field col m6 s12">
            <input id="nro_registro" type="text" v-model="form.nro_registro">
            <label for="nro_registro">Número de registro</label>
          </div>
        </div>
        <div class="row">
          <div class="col m6 s12">
            <label for="fecha_vencimiento">Fecha de vencimiento</label><br>
            <vue-datepicker placeholder="Fecha de vencimiento" :date="form.fecha_vencimiento" :option="option"></vue-datepicker>
          </div>
          <div class="input-field col m6 s12">
            <input id="lema_comercial" type="text" v-model="form.lema_comercial">
            <label for="lema_comercial">Lema comercial</label>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <textarea class="materialize-textarea" v-model="form.distincion_producto_servicio" id="distincion_producto_servicio"></textarea>
              <label for="distincion_producto_servicio">Distinción producto servicio</label>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
      <button class="btn blue waves-effect" @click="submit">Guardar</button>
    </div>
  </div>
  <div class="container">
    <br>
      <div class="card z-depth-1">
        <div class="card-image">
          <img src="img/sample-1.jpg" height="100px">
          <span class="card-title">Marcas</span>
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
     <table-column show="signo_distintivo" label="" :sortable="false" :filterable="false">
       <template scope="row">
        <a :href="laroute('marcas.show', {marca: row.id} )">
          <img style="cursor: pointer;" class="circle responsive-img materialboxed" :src="laroute('home') + 'storage/marcas/' + row.signo_distintivo">
        </a>
       </template>
     </table-column>
     <table-column show="nombre" label="Nombre">
       <template scope="row">
         <a :href="laroute('marcas.show', {marca: row.id} )">@{{row.nombre}}</a>
       </template>
     </table-column>
     <table-column show="clase" label="Clase"></table-column>
     <table-column show="distincion_producto_servicio" label="Distinción"></table-column>
     <table-column show="estado" label="Estado"></table-column>
     <table-column show="lema_comercial" label="Lema Comercial"></table-column>
     <table-column show="nro_incripcion" label="Nro Inscripcion"></table-column>
     <table-column show="nro_registro" label="Nro Registro"></table-column>
     <table-column show="fecha_vencimiento" label="Fecha Venc" data-type="date:DD/MM/YYYY">
       <template scope="row">
        <template v-if="row.fecha_vencimiento">
          @{{ row.fecha_vencimiento | filterDate }}
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
