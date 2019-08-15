@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col m8 offset-m2">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Reiniciar Contraseña</span>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="input-field col m8 s12">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    <label for="email">Correo Electrónico</label>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col m4 center">
                                    <button type="submit" class="btn blue password-reset">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
