@extends('layout.main')
@section('title', 'Configuración de Correos Automáticos')

@section('content')
<div id="app">
  <div class="container" style="padding: 1% 0">
    <div class="card" style="margin:0">
      <div class="card-content">
        <a href="{{route('correos.create')}}" class="btn blue waves-effect">Crear Correo</a>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>ID Plantilla</th>
              <th>Variables</th>
              <th>Correo Destino</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($correos as $index => $correo)
            <tr v-for="(correo, index) in correos">
              <td>{{ ($index+1) }}</td>
              <td>{{ $correo->id_plantilla }}</td>
              <td>{{ $correo->variables }}</td>
              <td>{{ $correo->correo_destino }}</td>
              <td>
                <a href="{{ route('correos.edit', $correo->id) }}">
                  <i class="material-icons" style="float:left">edit</i>
                </a>
                <form action="{{ route('correos.destroy', $correo->id) }}" method="DELETE" id="form-delete-correos-{{$correo->id}}">
                  <a href="" class="data-delete" data-form="correos-{{ $correo->id }}">
                    <i class="material-icons" style="float:left">delete</i>
                  </a>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(function () {
    $('.data-delete').on('click', function (e) {
      if (!confirm('¿Estás seguro de que quieres eliminar esto?')) return;
      e.preventDefault();
      $('#form-delete-' + $(this).data('form')).submit();
    });
  });
</script>
@endsection
