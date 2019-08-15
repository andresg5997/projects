@extends('layout.main')
@section('title', 'Dashboard')

@section('styles')
<style>
.collapsible-header.active{
  background: #448aff;
  color: white;
}
.collapsible-header.active{
  transition-duration: .5s;
  transition-property: background;
}
</style>
@endsection

@section('content')
<div id="app">
  {{-- Modal Nueva Tarea --}}
  <div id="taskModal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Crear nueva tarea</h4>
      <div class="row">
        <form class="col s12" enctype="multipart/form-data" @submit.prevent="submit">
          <div class="row">
            <div class="input-field col s6">
              <i class="material-icons prefix">work</i>
              <select v-material-select-marcas>
                <option value="" disabled selected>Cliente relacionado</option>
                <option value="0">Ninguna</option>
                <option v-for="marca in marcas" :value="marca.id" class="circle left">@{{ marca.nombre }}</option>
              </select>
            </div>
            <div class="input-field col s6">
              <i class="material-icons prefix">account_circle</i>
              <select v-material-select-usuarios class="icons" required>
                <option value="" disabled selected>Persona a asignar</option>
                <option v-for="usuario in usuarios" :value="usuario.id">@{{ usuario.nombre + ' ' + usuario.apellido }}</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">title</i>
              <input id="titulo" type="text" v-model="form.titulo">
              <label for="titulo">Título de la tarea</label>
              <span v-if="errors.titulo" class="red-text">@{{ errors.titulo[0] }}</span>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix">mode_edit</i>
              <textarea id="descripcion" v-model="form.descripcion" class="materialize-textarea"></textarea>
              <label for="descripcion">Descripción de la tarea</label>
              <span v-if="errors.descripcion" class="red-text">@{{ errors.descripcion[0] }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col m6 s12">
              <label for="fecha_vencimiento">Fecha de vencimiento</label>
              <br>
              <input type="text" class="datepicker" v-model="form.fecha_vencimiento.time">
              <span v-if="errors.fecha_vencimiento" class="red-text">@{{ errors.fecha_vencimiento[0] }}</span>
            </div>
            <div class="col m6 s12">
              <div class="file-field input-field">
                <div class="btn blue">
                  <span>Subir</span>
                  <input type="file" multiple @change="filesChanged">
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path" type="text" placeholder="Subir uno o más archivos">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="btn-flat waves-effect modal-close">Cancel</a>
      <button type="submit" class="btn blue waves-effect waves-light" @click="submit">Guardar</button>
    </div>
  </div>
  {{-- End Modal Nueva Tarea --}}
  <br>
  {{-- Lateral Tareas --}}
  <div class="row" style="margin-bottom: 0">
    <div class="col s12 m6">
      <div class="card z-depth-2">
        <div class="card-image">
          <h4 style="margin-left: 25px; padding-top: 20px">Mis actividades</h4>
          <a class="btn-floating btn-large hoverable halfway-fab waves-effect waves-light blue modal-trigger" href="#taskModal"><i class="material-icons">add</i></a>
          <div class="divider"></div>
        </div>
        <br>
        <div class="card-content">
          <ul class="collapsible popout" data-collapsible="accordion">
            @foreach($tareas as $index => $tarea)
            <li>
              <div class="collapsible-header hoverable">
                {{ $tarea->titulo }}<br>
                @if($tarea->marca)
                Cliente: {{ $tarea->marca->nombre }}
                @endif
                <span class="badge white-text
                @if($tarea->estado == 'Pendiente')
                green darken-1
                @elseif($tarea->estado == 'Para hoy')
                yellow darken-2
                @elseif($tarea->estado == 'Vencida')
                red darken-1
                @endif
                ">
                {{ $tarea->estado }}
              </span>

            </div>
            <div class="collapsible-body">
              <div class="row">
                <div class="col s10">
                  <b>Fecha de vencimiento</b>: {{ $tarea->fecha_vencimiento->format('Y-m-d') }}
                  @php
                  $dias = date_create(date('Y-m-d'))->diff(date_create($tarea->fecha_vencimiento->format('Y-m-d')))->format('%a');
                  $fecha_signo = date_create(date('Y-m-d'))->diff(date_create($tarea->fecha_vencimiento->format('Y-m-d')))->format('%R');
                  @endphp
                  @if($dias == 0)
                  ( Hoy )
                  @elseif($dias == 1)
                  ( @if($fecha_signo == '+')En @else Hace @endif{{ $dias }} día )
                  @else
                  ( @if($fecha_signo == '+')En @else Hace @endif{{ $dias }} días )
                  @endif
                  <br>
                  @if($tarea->marca)
                  <b>Para el cliente</b>: <a href="marcas/{{$tarea->marca->id}}">{{ $tarea->marca->nombre }}</a>
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col s12 m7">
                  <b>Descripción:</b>
                  <p>{{ $tarea->descripcion }}</p>
                  <br>
                  <a class="btn blue waves-effect" href="tareas/{{ $tarea->id }}">Ir a tarea <i class="material-icons right">send</i></a>
                </div>
                @if($tarea->archivos && count($tarea->archivos) > 0)
                <div class="col s12 m5">
                  <b>Archivos:</b>
                  @foreach($tarea->archivos as $archivo)
                  <i class="material-icons">file_download</i>
                  <a href="storage/archivos/{{ $archivo->nombre_archivo }}" download="{{$archivo->titulo}}"> {{ $archivo->titulo }}</a>
                  @endforeach
                </div>
                @endif
              </div>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  {{-- End Lateral Tareas --}}
  {{-- Lateral Indicadores Dashboard --}}
  <div class="col s12 m6">
    @if(count($dashboardClientes) == 0)
    <div class="card-panel z-depth-2">
      <h4>No hay paneles de dashboard.</h4>
      <a href="{{ route('home.config') }}" class="btn blue waves-effect">Crear paneles</a>
    </div>
    @else
    @foreach($dashboardClientes as $index => $clientes)
    <div class="card-panel z-depth-2">
      @if($dashboards[$index]->estado)
      <h5>Clientes que han tenido estado: <b>"{{ $dashboards[$index]->estado->nombre }}"</b> en los últimos: <b>{{ $dashboards[$index]->dias_estado }}</b> días</h5>
      <div class="row">
        <div class="col s12">
          <table class="responsive-table highlight bordered">
            <thead>
              <th>Nombre</th>
              <th>Fecha</th>
            </thead>
            <tbody>
              @foreach($clientes as $indexCliente => $cliente)
              <tr>
                <td><a href="{{route('marcas.show', $cliente['id'])}}">{{ $cliente['nombre'] }}</a></td>
                <td>{{ $fechas[$index][$indexCliente] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @else
      <h5>El estado asignado a este panel fue eliminado, porfavor cambie el estado o elimine el panel</h5>
      @endif
    </div>
    @endforeach
    @endif
  </div>
  {{-- End Lateral Indicadores Dashboard --}}
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var storage = {
    user_id: {{ Auth::id() }},
    csrf_token: '{{ csrf_token() }}',
    hoy: '{{ $hoy }}',
    tareas: {!! $tareasObj !!}
  }
</script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
@endsection
