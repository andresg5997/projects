@extends('layout.main')
@section('title', 'Transacciones')

@section('content')
<br><br>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h5>Lista de movimientos</h5>
                            <br>
                            <table class="responsive-table striped highlight bordered">
                                <thead>
                                    <th>ID</th>
                                    <th>Estado</th>
                                    <th>Marca</th>
                                    <th>Usuario</th>
                                    <th>Datos</th>
                                    <th>Fecha</th>
                                </thead>
                                <tbody>
                                    <tr v-for="transaccion in transacciones">
                                        <td>@{{ transaccion.id }}</td>
                                        <td>@{{ transaccion.estado.nombre }}</td>
                                        <td>@{{ transaccion.marca.nombre }}</td>
                                        <td>@{{ transaccion.usuario.nombre }}</td>
                                        <td>@{{ transaccion.datos }}</td>
                                        <td>@{{ transaccion.fecha | humanize }} (@{{ transaccion.fecha | filter }})</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/transacciones/index.js') }}"></script>
@endsection
