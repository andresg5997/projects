@extends('layout.main')
@section('title', 'Estados de Marca')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

@section('content')
    <br>
    <div id="app" class="container">
        <div class="card">
            <div class="card-content">
                <a href="/estados/create" class="btn blue right">Crear nuevo</a>
                <h5>Lista de Estados</h5>
                <table class="table-responsive striped bordered highlight">
                    <thead>
                        <th>ID</th>
                        <th><i class="material-icons left">assignment</i> Nombre</th>
                        <th><i class="material-icons left">archive</i> Requisitos</th>
                        {{-- <th><i class="material-icons left">format_list_numbered</i>Tareas</th> --}}
                        <th><i class="material-icons left">forward</i>Estado posterior</th>
                        <th><i class="material-icons left">assistant</i> Acciones</th>
                    </thead>
                    <tbody>
                        <tr v-for="(estado, index) in estados">
                            <td>@{{ estado.id }}</td>
                            <td><a :href="laroute('home') + 'estados/' + estado.id + '/edit'" title="">@{{ estado.nombre }}</a></td>
                            <td>
                                <p v-for="(requisito, key) in estado.requisitos">
                                    @{{ key }}: @{{ requisito }}
                                </p>
                            </td>
                            <td>
                                <p v-if="estado.posteriores.length > 0" v-for="posterior in estado.posteriores">
                                    @{{ posterior.nombre }}
                                </p>
                            </td>
                            {{-- <td>
                               <ul>
                                   <li v-for="tarea in estado.tareas">
                                        <i class="material-icons left">check</i> @{{ tarea.titulo }}
                                   </li>
                               </ul>
                            </td> --}}
                            <td>
                                <a :href="laroute('home') + 'estados/' + estado.id + '/edit'" class="tooltipped" data-tooltip="Editar" data-position="bottom" data-delay="50">
                                    <i class="material-icons">mode_edit</i>
                                </a>
                                <a href="#!" @click="deleteEstado(estado.id, index)" class="red-text">
                                    <i class="material-icons">remove</i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    {{-- nombre
            estado_posterior
            requisitos
            tiempo_seguimiento --}}
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const storage = {
            csrf_token: '{{ csrf_token() }}'
        }
    </script>
    <script type="text/javascript" src="{{ asset('js/estados/index.js') }}"></script>
@endsection
