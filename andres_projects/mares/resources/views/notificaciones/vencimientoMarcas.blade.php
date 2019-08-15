@extends('notificaciones.layout.main')

@section('content')
    <div class="container">
        <div class="card z-depth-2">
            <div class="card-content">
                <p>{{ date('d-m-Y') }}</p>
                <h5>Marcas vencidas: {{ count($vencidas) }}</h5><br>
                <table class="striped">
                    <thead>
                        <th>Nombre</th>
                        <th>Fecha de vencimiento</th>
                    </thead>
                    <tbody>
                        @foreach($vencidas as $vencida)
                            <tr>
                                <td><a href="{{ route('marcas.show', $vencida->id) }}">{{ $vencida->nombre }}</a></td>
                                <td>{{ date('d-m-Y', strtotime($vencida->fecha_vencimiento)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br><br>
                <h5>Marcas por vencer: {{ count($porVencer) }}</h5><br>
                <table class="striped">
                    <thead>
                        <th>Nombre</th>
                        <th>Fecha de vencimiento</th>
                    </thead>
                    <tbody>
                        @foreach($porVencer as $marca)
                            <tr>
                                <td><a href="{{ route('marcas.show', $marca->id) }}">{{ $marca->nombre }}</a></td>
                                <td>{{ date('d-m-Y', strtotime($marca->fecha_vencimiento)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
