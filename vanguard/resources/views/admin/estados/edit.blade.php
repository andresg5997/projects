@extends('layout.main')
@section('title', 'Estados de Marca')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/vue-multiselect.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
<br>
<div id="app" class="container">
  <div class="card">
    <div class="card-content">
      <form @submit.prevent="store" v-if="estado">
        <div class="row">
          <div class="col s6">
            <h5>Editar estado</h5>
            <div class="input-field">
              <label for="nombre">Nombre de estado</label>
              <input type="text" name="nombre" id="nombre" v-model="form.nombre" placeholder="Nombre de estado">
              <span class="red-text" v-if="errors.nombre">@{{ errors.nombre[0] }}</span>
            </div>
          </div>
          <div class="card col s6">
            <div class="card-content">
              <b>Estado anterior</b>
              <p v-for="anterior in estado_anterior" v-if="anterior">@{{ anterior.nombre }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col s6">
            <label>Proceso</label>
            <multiselect @select="getEstados()" v-model="form.proceso"
            :hide-selected="true" :options="procesos"
            label="nombre" placeholder="Escoger proceso"
            select-label="Presiona enter para elegir"
            selected="Elegido"
            deselect-label="Presiona enter para quitar"
            >
            </multiselect>
          </div>
        <div class="col s6">
          {{-- <template v-if="form.proceso.estados"> --}}
            <label>Estado(s) posterior(es)</label>
            <multiselect v-model="form.estado_posterior" :hide-selected="true"
            :options="estados" label="nombre" :multiple="true"
            placeholder="Escoger opciones" select-label="Presiona enter para elegir"
            selected="Elegido" deselect-label="Presiona enter para quitar"
            >
            </multiselect>
          {{-- </template> --}}
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <label for="titulo_tarea">Titulo de tarea principal</label>
          <input id="titulo_tarea" type="text" v-model="form.titulo_tarea" required>
        </div>
        <div class="col s6">
          <label>Tiempo de seguimiento (en días)</label>
          <input type="number" min="0" max="99999" v-model="form.tiempo_seguimiento" required>
        </div>
      </div>
      <div class="divider"></div>
      <br><br>
      <div class="row">
        <div class="col s12">
          <button type="button" @click="agregarRequisito" class="btn blue right">Añadir actividad</button>
          <h5>Actividades</h5>
          <br><br>
          <strong>Lista de actividades</strong><br>
          <span class="red-text" v-if="Object.keys(errors).length > 1">
            El nombre y tipo de todas las actividades son requeridos.
          </span>
          <p v-if="errors.requisitos" class="red-text">Los @{{ errors.requisitos[0] }}</p>
          <div class="row" style="margin: 25px 0!important;" v-for="(requisito, index) in form.requisitos">
            <div class="col s1 valign-wrapper">
              <a href="#!" @click="quitarRequisito(index)" data-tooltip="Quitar" class="right red-text tooltipped" data-position="bottom" data-delay="50" style="font-size: 48px">
                &times;
              </a>
            </div>
            <div class="col s4 input-field">
              <i class="material-icons prefix">assignment</i>
              <input :id="'requisito' + index" :name="'requisito' + index" :placeholder="'Actividad ' + (index+1)" type="text" v-model="form.requisitos[index].nombre">
            </div>
            <div class="col s3">
              <multiselect @select="cambiarTipo(index, $event)" :hide-selected="true" :options="tipos"
              :value="tipos[requisito.index]"
              label="nombre" placeholder="Escoger tipo"
              select-label="Presiona enter para elegir"
              selected="Elegido"
              deselect-label="Presiona enter para quitar"
              :show-labels="false"
              >
              </multiselect>
            </div>
            <template v-if="requisito.tipo == 'select'">
              <div class="col s4">
                <center>
                  <h5>Opciones</h5>
                  <button type="button" class="btn blue" @click="agregarOpcion(index)">Añadir opción</button>
                </center>
                <div class="row" v-for="(opcion, indexOpc) in requisito.opciones">
                  <div class="col s11">
                    <div class="input-field">
                      <i class="material-icons blue-text text-darken-4 prefix">keyboard_arrow_right</i>
                      <input type="text" v-model="opcion.nombre">
                    </div>
                  </div>
                  <div class="col s1">
                    <a href="#!" @click="quitarOpcion(index,indexOpc)" data-tooltip="Quitar" class="right red-text tooltipped" data-position="bottom" data-delay="50" style="font-size: 48px">
                      &times;
                    </a>
                  </div>
                </div>
              </div>
            </template>
            <template v-if="requisito.tipo == 'auto'">
              <div class="col s4">
                <multiselect v-model="requisito.opciones" :hide-selected="true" :options="tipos_auto"
                label="nombre" placeholder="Tipo de tarea automatizada"
                select-label="Presiona enter para elegir"
                selected="Elegido"
                deselect-label="Presiona enter para quitar"
                :show-labels="false"
                >
                </multiselect>
              </div>
              <template v-if="requisito.opciones.tipo == 'email'">
                <div class="col s12">
                  <div class="col s3 offset-s1">
                    <multiselect v-model="requisito.opciones.tipo_tarea"  :options="tipo_tarea_email"
                    label="id_plantilla" placeholder="Tipo de tarea automatizada"
                    select-label="Presiona enter para elegir" :hide-selected="true"
                    deselect-label="Presiona enter para quitar"
                    selected="Elegido" :show-labels="false"
                    >
                    </multiselect>
                  </div>
                  <div class="col s3">
                    <multiselect v-model="requisito.opciones.tipo_fecha"
                    :options="tipo_fecha_auto" label="nombre"
                    placeholder="Fecha de envío" :value="requisito"
                    select-label="Presiona enter para elegir" :hide-selected="true"
                    deselect-label="Presiona enter para quitar"
                    selected="Elegido" :show-labels="false"
                    >
                    </multiselect>
                  </div>
                  <div class="col s3" v-if="requisito.opciones.tipo_fecha.tipo == 'fecha'">
                    <input placeholder="Ingrese tiempo de envío (Dias)" type="number" v-model="requisito.opciones.fecha">
                  </div>
                  <div class="col s2">
                    <b>Correo Destino:</b>
                    <p>
                      <template v-if="requisito.opciones.tipo_tarea.correo_destino == 'cliente'">
                        Cliente al que se le hace la tarea
                      </template>
                      <template v-else-if="requisito.opciones.tipo_tarea.correo_destino == 'tarea'">
                        Para ingresar al momento de hacer la tarea
                      </template>
                      <template v-else>
                        @{{ requisito.opciones.tipo_tarea.correo_destino }}
                      </template>
                    </p>
                  </div>
                </div>
              </template>
            </template>
            <template v-if="requisito.tipo == 'cliente'">
              <div class="col s4">
                <multiselect v-model="requisito.opciones" :hide-selected="true"
                :options="tipos_campo_cliente" label="nombre"
                placeholder="Campo a editar del cliente"
                select-label="Presiona enter para elegir"
                selected="Elegido"
                deselect-label="Presiona enter para quitar"
                :show-labels="false"
                >
                </multiselect>
              </div>
            </template>
            </div>
          </div>
        </div>
      <button type="submit" class="btn blue waves-effect">Guardar</button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const storage = {
    csrf_token: '{{ csrf_token() }}',
    estado_id: {{ $estado->id }},
    estado_posterior: '{{ $estado->estado_posterior }}'
    @if(isset($proceso))
    ,
    proceso_id: '{{ $proceso->id }}'
    @endif
  }
</script>
<script type="text/javascript" src="{{ asset('js/estados/edit.js') }}"></script>
@endsection
