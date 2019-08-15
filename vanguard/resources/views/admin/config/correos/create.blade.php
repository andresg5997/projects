@extends('layout.main')
@section('title', 'Crear Correo Automático')
@section('content')
<div id="app">
    <div class="container" style="padding: 1% 0">
        <div class="card" style="margin:0">
            <div class="card-content">
                <div class="row">
                    <form action="{{ action('CorreosController@store') }}" method="post">
                        {{csrf_field()}}
                        <div class="col s6">
                            <div class="input-field">
                                <label for="id_plantilla">ID Plantilla</label>
                                <input type="text" id="id_plantilla" name="id_plantilla">
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <select v-material-select-1 name="variables[]" multiple>
                                    <option value="" disabled selected>Variables del correo</option>
                                    <optgroup label="Cliente">
                                    @foreach($variables[0] as $variable_nombre => $variable_cliente)
                                        <option value="{{ $variable_cliente }}">{{ $variable_nombre }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Estado">
                                    @foreach($variables[1] as $variable_nombre => $variable_estado)
                                        <option value="{{ $variable_estado }}">{{ $variable_nombre }}</option>
                                    @endforeach
                                    </optgroup>
                                    @foreach($variables[2] as $nombre_proceso => $variable_proceso)
                                        <optgroup label="{{ $nombre_proceso }}">
                                        @foreach($variable_proceso as $nombre_estado => $estado_requisitos)
                                            <optgroup label="{{ $nombre_estado }}">
                                                @foreach($estado_requisitos as $requisito)
                                                    <option value="{{ $nombre_estado }}.{{ $requisito }}">{{ $requisito }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <select id="correo_select" v-material-select name="correo_select">
                                    <option disabled value="" selected>Seleccione un destino de email</option>
                                    <option value="cliente">Cliente al que se le hace la tarea</option>
                                    <option value="tarea">Para ingresar al momento de hacer la tarea</option>
                                    <option value="blanco">Ingresar correo electrónico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col s6" v-if="correo_select == 'blanco'">
                            <div class="input-field">
                                <label for="correo_destino">Correo destino</label>
                                <input type="email" id="correo_destino" name="correo_destino">
                            </div>
                        </div>
                        <div class="col s12">
                            <center><button type="submit" class="btn blue waves-effect">Guardar</button></center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/config/correos/create.js') }}"></script>
@endsection
