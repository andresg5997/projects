@extends('layout.main')
@section('title', 'Realizar transacción')


@section('content')
<br><br>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col s12">
                                    Transacción de <h5>@{{ estado.nombre }}</h5>
                                    <p><b>Para la marca:</b> <a href="#">@{{ tarea.marca.nombre }}</a></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12">
                                    <form @submit.prevent="submit">
                                        <div class="input-field">
                                            <label v-if="hasMultiple" for="posterior">Estado posterior:</label>
                                            <select id="posterior" v-material-select v-if="hasMultiple" required>
                                                {{-- <option value="" selected disabled></option> --}}
                                                <option :value="estado.id" v-for="estado in posteriores">
                                                    @{{ estado.nombre }}
                                                </option>
                                            </select>
                                        </div>
                                        <template v-for="(tipo, requisito, index) in estado.requisitos">
                                            <div v-if="isFile(tipo)">
                                                <div class="file-field input-field">
                                                  <div class="btn">
                                                    <span>Cargar</span>
                                                    <input type="file" :name="requisito" @change="file(index, $event)">
                                                  </div>
                                                  <div class="file-path-wrapper">
                                                    <input class="file-path validate" type="text">
                                                  </div>
                                                </div>
                                            </div>

                                            <div class="input-field" v-else>
                                                <label :for="requisito">@{{ humanize(requisito) }}</label>
                                                <input :type="tipo" :name="requisito" :id="requisito" @change="inputChanged(index, $event)">
                                            </div>
                                        </template>

                                        <div class="row" style="margin-top: 10px">
                                            <div class="col s4">
                                                <b for="fecha_vencimiento">Fecha de vencimiento</b>
                                                <br>
                                                <vue-datepicker :date="form.fecha_vencimiento" :option="option"></vue-datepicker>
                                                <p v-if="errors.fecha_vencimiento" class="red-text">@{{ errors.fecha_vencimiento[0] }}</p>
                                            </div>
                                            <div class="col s8">
                                                <b for="asignar">Asignar la siguiente tarea a:</b>
                                                <div class="input-field">
                                                    <select v-material-select-asignar id="asignar">
                                                        <option value="0" selected>Nadie</option>
                                                        <option :value="usuario.id" v-for="usuario in usuarios">@{{ usuario.nombre + ' ' + usuario.apellido }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div align="center">
                                            <button type="submit" class="btn blue">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const storage = {
        auth_id: {{ Auth::id() }},
        csrf_token: '{{ csrf_token() }}',
        tarea_id: {{ $tarea_id }}
    }
</script>
<script src="{{ asset('js/transacciones/create.js') }}"></script>
@endsection
