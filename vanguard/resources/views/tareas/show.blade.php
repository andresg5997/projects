@extends('layout.main')
@section('title', 'Ver tarea')

@section('content')
{{-- <br><br> --}}
<div id="root">
  <div class="container">
    <div class="row">
      <div class="col s12">
        <div class="card">
          <div class="card-content">
            <div class="row">
              <div class="col s8">
                <h4 v-if="!edit">@{{ tarea.titulo }}</h4>
                <div class="input-field" v-else>
                  <input type="text" name="titulo" v-model="form.titulo">
                </div>
              </div>
              <div class="col s2" v-if="!this.tarea.transaccion && !this.tarea.estado">
                <input type="checkbox" class="filled-in" :id="'tarea-' + tarea.id" :checked="tarea.status === '1'" @change="changeStatus(tarea, $event)">
                <label :for="'tarea-' + tarea.id">Hecho</label>
              </div>
              <div class="col s2">
                <button type="button" class="btn blue waves-effect" @click="edit = !edit">Editar</button>
              </div>
            </div>
            <br><br>
            <div class="row">
              <div class="col s3">
                <i class="material-icons left blue-text">today</i>
                <b>Fecha de vencimiento</b>:<br>
                @{{ tarea.fecha_vencimiento | moment}}
                (@{{ tarea.fecha_vencimiento | fromNow}})
              </div>
              <div class="col s3">
                <template v-if="tarea.status === '1'">
                  <i class="material-icons left green-text">check_box</i>
                  <b class="green-text">Realizada</b>
                </template>
                <template v-else>
                  <i class="material-icons left">check_box_outline_blank</i>
                  <b class="red-text">No realizada</b>
                </template>
              </div>
              <div class="col s3" v-if="tarea.asignado_a">
                <i class="material-icons left blue-text">assignment_ind</i>
                <b>Asignado a</b>:
                <a :href="laroute('home') + 'usuarios/' + tarea.asignado_a.id">
                  @{{ tarea.asignado_a.nombre + ' ' + tarea.asignado_a.apellido}}
                </a>
              </div>
              <div class="col s3" v-if="tarea.usuario">
                <i class="material-icons left black-text">assignment_ind</i>
                <b>Creada por</b>:
                <a :href="laroute('home') + 'usuarios/' + tarea.usuario.id">
                  @{{ tarea.usuario.nombre  + ' ' + tarea.usuario.apellido}}
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col s9">
                <p v-if="!edit">
                  @{{ tarea.descripcion }}
                </p>
                <template v-else>
                  <div class="input-field">
                    <textarea v-model="form.descripcion">@{{ tarea.descripcion }}</textarea>
                  </div>
                  <br>
                  <div align="center">
                    <button type="button" @click="update" class="btn blue waves-effect">Guardar cambios</button>
                  </div>
                </template>
              </div>
            </div>
            <hr>
            <div class="row" v-if="tarea.transaccion">
              <div class="col s12">
                <h5 class="green-text">Transacción realizada</h5>
                <span v-if="tarea.transaccion.datos.length == 0">
                  Sin datos.
                </span>
                <template v-else v-for="dato in tarea.transaccion.datos">
                  <b>@{{ dato.requisito | humanize }}</b>:<br>
                  <div v-if="dato.tipo == 'file'">
                    <a :href="laroute('home') + 'storage/archivos/' + dato.valor">@{{ dato.requisito }}</a>
                  </div>
                  <div v-if="dato.tipo == 'cliente'">
                    <span v-if="dato.valor.nombre">@{{ dato.valor.nombre }}</span>
                    <span v-if="dato.valor.apellido">@{{ dato.valor.apellido }}</span>
                    <span v-if="dato.valor.email">@{{ dato.valor.email }}</span>
                    <span v-if="dato.valor.ciudad">@{{ dato.valor.ciudad }}</span>
                    <span v-if="dato.valor.pais">@{{ dato.valor.pais }}</span>
                    <span v-if="dato.valor.direccion">@{{ dato.valor.direccion }}</span>
                  </div>
                  <div v-else>
                    <span>@{{ dato.valor }}</span>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="tarea && tarea.estado && tarea.status == '0'">
      <div class="col s12">
        <transaccion-create @updated="getData()" :tarea="tarea" csrf_token="{{ csrf_token() }}" user_id="{{ Auth::id() }}"></transaccion-create>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const storage = {
    user_id: {{ Auth::id() }},
    tarea_id: {{ $tarea->id }},
    csrf_token: '{{ csrf_token() }}',
    hoy: '{{ $hoy }}'
  }
</script>
<script src="{{ asset('js/tareas/show.js') }}"></script>
@endsection
