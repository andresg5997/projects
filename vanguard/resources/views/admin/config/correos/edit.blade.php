@extends('layout.main')
@section('title', 'Editar Correo Automático')
@section('content')
<div id="app">
    <div class="container" style="padding: 1% 0">
        <div class="card" style="margin:0">
            <div class="card-content">
                <div class="row">
                    <form action="{{ action('CorreosController@update', $correo->id) }}" method="post">
                        {{csrf_field()}}
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="col s6">
                            <div class="input-field">
                                <label for="id_plantilla">ID Plantilla</label>
                                <input type="text" id="id_plantilla" name="id_plantilla" value="{{ $correo->id_plantilla }}">
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <select v-material-select-1 name="variables[]" multiple>
                                    <option value="" disabled selected>Variables del correo</option>
                                    <optgroup label="Cliente">
                                    @foreach($variables[0] as $variable_nombre => $variable_cliente)
                                        <option value="{{ $variable_cliente }}"
                                            @if($correo->variables)
                                                @foreach($correo->variables as $variable)
                                                    @if($variable_cliente == $variable)
                                                        selected
                                                    @endif
                                                @endforeach
                                            @endif
                                        >{{ $variable_nombre }}</option>
                                    @endforeach
                                    </optgroup>
                                    <optgroup label="Estado">
                                    @foreach($variables[1] as $variable_nombre => $variable_estado)
                                        <option value="{{ $variable_estado }}"
                                            @if($correo->variables)
                                                @foreach($correo->variables as $variable)
                                                    @if($variable_estado == $variable)
                                                        selected
                                                    @endif
                                                @endforeach
                                            @endif
                                        >{{ $variable_nombre }}</option>
                                    @endforeach
                                    </optgroup>
                                    @foreach($variables[2] as $nombre_proceso => $variable_proceso)
                                        <optgroup label="{{ $nombre_proceso }}">
                                        @foreach($variable_proceso as $nombre_estado => $estado_requisitos)
                                            <optgroup label="{{ $nombre_estado }}">
                                                @foreach($estado_requisitos as $requisito)
                                                    <option value="{{ $nombre_estado }}.{{ $requisito }}"
                                                        @php
                                                        $nombre_requisito = $nombre_estado . "." . $requisito;
                                                        @endphp
                                                            @if($correo->variables)
                                                            @foreach($correo->variables as $variable)
                                                                @if($nombre_requisito == $variable)
                                                                    selected
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    >{{ $requisito }}</option>
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
                                    <option value="cliente"
                                        @if($correo->correo_destino == "cliente")
                                            selected
                                        @endif
                                    >Cliente al que se le hace la tarea</option>
                                    <option value="tarea"
                                        @if($correo->correo_destino == "tarea")
                                            selected
                                        @endif
                                    >Para ingresar al momento de hacer la tarea</option>
                                    <option value="blanco"
                                        @if($correo->correo_destino != "cliente" && $correo->correo_destino != "tarea")
                                            selected
                                        @endif
                                    >Ingresar correo electrónico</option>
                                </select>
                            </div>
                        </div>
                        <div class="col s6" v-if="correo_select != 'cliente' && correo_select != 'tarea'">
                            <div class="input-field">
                                <label for="correo_destino">Correo destino</label>
                                <input type="email" id="correo_destino" name="correo_destino"
                                @if($correo->correo_destino != "cliente" && $correo->correo_destino != "tarea")
                                    value="{{$correo->correo_destino}}"
                                @endif
                                >
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
<script>
    var storage = {
        destino: "{{ $correo->correo_destino }}"
    }
</script>
<script type="text/javascript" src="{{ asset('js/config/correos/edit.js') }}"></script>
@endsection
