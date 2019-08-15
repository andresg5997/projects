@extends('layout.main')
@section('title', 'Configuración de Datos Adicionales')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/vue-multiselect.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div id="app">
    <div class="container" style="padding: 1%">
        <div class="card" style="margin:0">
            <div class="card-content">
                <div class="row">
                    <template v-for="(dato, index) in datosAdicionales">
                        <div class="col s6">
                            <div class="card grey lighten-4" style="border: 1px solid rgba(0,0,0,0.3)">
                                <div class="card-content">
                                    <div class="row">
                                        <i @click="borrarDato(index)" class="material-icons red-text right small" style="cursor: pointer">close</i>
                                        <center>
                                        <b>Dato #@{{index+1}}</b>
                                        </center>
                                        <div class="col s6">
                                            <label for="categoria">Categoría</label>
                                            <multiselect @select="selectCategoria($event, index)"
                                            :hide-selected="true" :options="opciones"
                                            label="categoria" placeholder="Escoger categoría"
                                            select-label="Presiona enter para elegir"
                                            selected="Elegido" :value="selectedCategorias[index]"
                                            deselect-label="Presiona enter para quitar"
                                            >
                                            </multiselect>
                                        </div>
                                        <div class="col s6">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" id="nombre" v-model="dato.nombre">
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <p class="red-text" v-if="errors[index]">Todos los campos son requeridos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="col s6">
                        <center><i @click="agregarDato()" class="material-icons large blue-text text-darken-2" style="cursor:pointer">add_circle</i></center>
                    </div>
                </div>
                <div class="row" v-if="showConfirmacion">
                    <p class="green-text right">¡Los datos fueron cargados correctamente!</p>
                </div>
                <div class="row">
                    <a @click="submit()" class="btn btn-large waves-effect waves-light hoverable blue darken-2 right" href="#!">Guardar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var storage = {
        csrf_token: '{{ csrf_token() }}'
    }
</script>
<script type="text/javascript" src="{{ asset('js/config/datosAdicionales.js') }}"></script>
@endsection

