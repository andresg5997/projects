@extends('layout.main')
@section('title', 'Oposiciones')
@section('content')
    <div class="container" id="app">
      <br>
        <div class="card z-depth-1">
          <div class="card-content">
            <h3 class="card-title">Buscador de posibles oposiciones</h3>
            <form action="{{ route('oposiciones.store') }}" enctype="multipart/form-data" method="POST">
              {!! csrf_field() !!}
              <div class="row">
                <div class="col s6">
                  <p class="range-field">
                    <label for="minimo_nombre">Porcentaje mínimo de similitud de nombres</label>
                    <input id="minimo_nombre" type="range" name="minimo_nombre" min="1" max="100" value="50">
                  </p>
                </div>
                <div class="col s6">
                  <p class="range-field">
                    <label for="minimo_distincion">Porcentaje mínimo de similitud de distinción</label>
                    <input type="range" name="minimo_distincion" min="1" max="100" value="50">
                  </p>
                </div>
              </div>
              <div class="row">
                <div class="file-field input-field col s12">
                  <div class="btn blue">
                    <span>Cargar PDF</span>
                    <input type="file" accept="application/pdf" required name="pdf">
                  </div>
                  <div class="file-path-wrapper">
                    <input class="file-path" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col s12" align="center">
                  <button type="submit" class="btn blue">Enviar</button>
                </div>
              </div>
            </form>
            @if(isset($marcas))
            <br><br>
            <div class="divider"></div>
              <h4 align="center">Porcentaje mínimo para nombre: {{ $minimo_nombre }}</h4>
              <h4 align="center">Porcentaje mínimo para distinción: {{ $minimo_distincion }}</h4>
              <br>
              @foreach($marcas as $marca)
                @if(count($marca->similar) > 0)
                  <div class="row">
                    <div class="col s2 center">
                      <img src="/storage/marcas/{{ $marca->signo_distintivo }}" class="circle responsive-img" alt="">
                      <h5><a href="{{ route('marcas.show', $marca->id) }}">{{ $marca->nombre }}</a></h5>
                      <p><strong>Distinción de producto/servicio:</strong></p>
                      <p>No tiene.</p>
                      @if($marca->distincion_producto_servicio)
                        <p>{{ $marca->distincion_producto_servicio }}</p>
                      @endif
                    </div>
                    <div class="col s10">
                      @if(isset($marca->similar))
                        @foreach($marca->similar as $similar)
                          <table class="responsive-table">
                            <thead>
                              <th>Nombre de la marca</th>
                              <th>Porcentaje similitud nombre</th>
                              <th>Porcentaje similitud distinción</th>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  {{ $similar['marca'][1] }}
                                </td>
                                <td>
                                  {{ number_format($similar['porcentaje_nombre'] * 100, 2, ',', '.') }}%
                                </td>
                                <td>{{ number_format($similar['porcentaje_distincion'] * 100, 2, ',', '.') }}%</td>
                              </tr>
                              <tr>
                                <th colspan="3">
                                  Para distinguir
                                </th>
                              </tr>
                              <tr>
                                <td colspan="3">
                                  {{ $similar['marca'][2] }}
                                </td>
                              </tr>
                              <tr>
                                <td colspan="3">
                                  <div class="divider"></div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          @php
                            unset($marca);
                          @endphp
                        @endforeach
                      @else
                        <h5>No se encontraron similitudes.</h5>
                      @endif
                    </div>
                  </div>
                  <div class="divider" style="margin: 20px 0px;"></div>
                @endif
              @endforeach
              <br><br>
            @endif
          </div>
        </div>
    </div>
    <br>
@endsection

@section('scripts')
<script>
  const storage = {
    csrf_token: '{{ csrf_token() }}'
  }
</script>
<script src="{{ asset('js/oposiciones/index.js') }}" type="text/javascript"></script>
@endsection
