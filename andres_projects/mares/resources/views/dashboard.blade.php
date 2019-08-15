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
                    <select v-material-select-marcas class="icons">
                      <option value="" disabled selected>Marca relacionada</option>
                      <option value="0">Ninguna</option>
                      <option v-for="marca in marcas" :value="marca.id" :data-icon="'/storage/marcas/' + marca.signo_distintivo" class="circle left">@{{ marca.nombre }}</option>
                    </select>
                  </div>
                  <div class="input-field col s6">
                      <i class="material-icons prefix">account_circle</i>
                      <select v-material-select class="icons">
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
                      {{-- <input id="fecha_vencimiento" type="text" @change="dateUpdated"> --}}
                      <br>
                      <vue-datepicker :date="form.fecha_vencimiento" :option="option"></vue-datepicker>
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
                      <li v-for="tarea in tareas">
                        <template v-if="checkTarea(tarea)">
                          <div class="collapsible-header hoverable">
                              @{{ tarea.titulo }}<br>
                              <template v-if="tarea.marca">
                                Marca: @{{ tarea.marca.nombre }}
                              </template>
                              <span class="badge white-text" :class="statusClass(tarea)">@{{ statusText(tarea) }}</span>
                          </div>
                          <div class="collapsible-body">
                            <div class="row">
                              <div class="col s10">
                                <b>Fecha de vencimiento</b>: @{{ tarea.fecha_vencimiento | format }}
                                  (@{{ tarea.fecha_vencimiento | fromNow }})
                                  <br>
                                  <template v-if="tarea.marca">
                                    <b>Para la marca</b>: <a :href="laroute('home') + 'marcas/' + tarea.marca.id">@{{ tarea.marca.nombre }}</a>
                                  </template>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col s12 m7">
                                <b>Descripción:</b>
                                <p>@{{ tarea.descripcion }}</p>
                                <br>
                                <a class="btn blue waves-effect" :href="laroute('home') + 'tareas/' + tarea.id">Ir a tarea <i class="material-icons right">send</i></a>
                              </div>
                              <div class="col s12 m5" v-if="tarea.archivos && tarea.archivos.length > 0">
                                  <b>Archivos:</b>
                                  <div v-for="archivo in tarea.archivos">
                                    <i class="material-icons">file_download</i>
                                    <a :href="laroute('home') + 'storage/archivos/' + archivo.nombre_archivo" :download="archivo.titulo"> @{{ archivo.titulo }}</a>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </template>
                      </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col s12 m6">
          <div class="card-panel z-depth-2">
                <h4>Marcas por vencer</h4>
                @if(count($porVencer) > 0)
                <div class="row">
                    <div class="col s12">
                        <table class="responsive-table highlight bordered">
                          <thead>
                            <th></th>
                            <th>Nombre</th>
                            <th>Fecha de vencimiento</th>
                          </thead>
                          <tbody>
                            @foreach($porVencer as $marca)
                              <tr>
                                <td class="center">
                                  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
                                </td>
                                <td>{{ $marca->nombre }}</td>
                                <td>{{ date('d-m-Y', strtotime($marca->fecha_vencimiento)) }} ({{ $marca->fecha_vencimiento->diffForHumans() }})</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                @else
                  <div class="row">
                    <div class="col s12">
                      <hr>
                      <strong>Sin resultados.</strong>
                    </div>
                  </div>
                @endif
            </div>
            <div class="card-panel z-depth-2">
                <h4>Marcas negadas en los últimos {{$dias['dias_negadas']}} días</h4>
                @if(count($marcas_negadas) > 0)
                  <div class="row">
                      <div class="col s12">
                          <table class="responsive-table highlight bordered">
                            <thead>
                              <th></th>
                              <th>Nombre</th>
                              <th>Estado</th>
                              <th>Última actualización</th>
                            </thead>
                            <tbody>
                              @foreach($marcas_negadas as $marca)
                                <tr>
                                  <td class="center">
                                    <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
                                  </td>
                                  <td>{{ $marca->nombre }}</td>
                                  <td>{{ $marca->estado }}</td>
                                  <td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                      </div>
                  </div>
                @else
                  <div class="row">
                    <div class="col s12">
                      <hr>
                      <strong>Sin resultados.</strong>
                    </div>
                  </div>
                @endif
            </div>
            <div class="card-panel z-depth-2">
                <h4>Marcas con orden de publicación en los últimos {{$dias['dias_publicacion']}} días</h4>
                @if(count($marcas_publicacion) > 0)
                <div class="row">
                    <div class="col s12">
                        <table class="responsive-table highlight bordered">
                          <thead>
                            <th></th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Última actualización</th>
                          </thead>
                          <tbody>
                            @foreach($marcas_publicacion as $marca)
                              <tr>
                                <td class="center">
                                  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
                                </td>
                                <td>{{ $marca->nombre }}</td>
                                <td>{{ $marca->estado }}</td>
                                <td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                @else
                  <div class="row">
                    <div class="col s12">
                      <hr>
                      <strong>Sin resultados.</strong>
                    </div>
                  </div>
                @endif
            </div>
            <div class="card-panel z-depth-2">
                <h4>Marcas concedidas en los últimos {{$dias['dias_concedidas']}} días</h4>
                @if(count($marcas_concedidas) > 0)
                <div class="row">
                    <div class="col s12">
                        <table class="responsive-table highlight bordered">
                          <thead>
                            <th></th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Última actualización</th>
                          </thead>
                          <tbody>
                            @foreach($marcas_concedidas as $marca)
                              <tr>
                                <td class="center">
                                  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
                                </td>
                                <td>{{ $marca->nombre }}</td>
                                <td>{{ $marca->estado }}</td>
                                <td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                @else
                  <div class="row">
                    <div class="col s12">
                      <hr>
                      <strong>Sin resultados.</strong>
                    </div>
                  </div>
                @endif
            </div>
            <div class="card-panel z-depth-2">
                <h4>Solicitudes devueltas en los últimos {{$dias['dias_devueltas']}} días</h4>
                @if(count($marcas_devueltas) > 0)
                <div class="row">
                    <div class="col s12">
                        <table class="responsive-table highlight bordered">
                          <thead>
                            <th></th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Última actualización</th>
                          </thead>
                          <tbody>
                            @foreach($marcas_devueltas as $marca)
                              <tr>
                                <td class="center">
                                  <img style="width:60px" class="circle" src="storage/marcas/{{ $marca->signo_distintivo }}">
                                </td>
                                <td>{{ $marca->nombre }}</td>
                                <td>{{ $marca->estado }}</td>
                                <td>{{ $marca->ultima_actualizacion->diffForHumans() }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                @else
                  <div class="row">
                    <div class="col s12">
                      <hr>
                      <strong>Sin resultados.</strong>
                    </div>
                  </div>
                @endif
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
  <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
@endsection
