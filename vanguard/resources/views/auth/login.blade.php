@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-tabs">
                <ul class="tabs tabs-fixed-width">
                    <li class="tab"><a class="blue-text" href="#login">Iniciar Sesión</a></li>
                    <li class="tab"><a class="blue-text" href="#register">Registrarse</a></li>
                </ul>
            </div>
            <div class="card-content">
                <div id="login">
                    <div class="container">
                        <span class="card-title">Iniciar Sesión</span>
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="input-field col s12">
                                    <input id="email" type="email" name="email" class="validate" value="{{ old('email') }}" required autofocus>
                                    <label for="email">Correo Electrónico</label>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    <label for="password">Contraseña</label>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col m6">
                                    <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">Recordarme</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col m8 offset-m4">
                                    <button type="submit" class="btn blue">
                                        Entrar
                                    </button>
                                    <a class="waves-effect waves-blue btn-flat btn-link" href="{{ route('password.request') }}">
                                        ¿Olvido su contraseña?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="register">
                    <div class="container">
                        <span class="card-title">Registrarse </span>
                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col s6">
                                    <div class="{{ $errors->has('nombre') ? ' has-error' : '' }}">
                                        <div class="input-field">
                                            <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required>
                                            <label for="nombre">Nombre</label>
                                            @if ($errors->has('nombre'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('nombre') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="{{ $errors->has('apellido') ? ' has-error' : '' }}">
                                        <div class="input-field">
                                            <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required>
                                            <label for="apellido">Apellido</label>
                                            @if ($errors->has('apellido'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('apellido') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <div class="input-field">
                                            <input id="email" type="email" name="email" value="{{ old('email') }}">
                                            <label for="email">Correo Electrónico</label>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field">
                                        <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}">
                                        <label for="telefono">Teléfono</label>
                                        @if ($errors->has('telefono'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('telefono') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <div class="input-field">
                                        <input id="departamento" type="text" name="departamento" value="{{ old('departamento') }}">
                                        <label for="departamento">Departamento</label>
                                        @if ($errors->has('departamento'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('departamento') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field">
                                        <input id="cargo" type="text" name="cargo" value="{{ old('cargo') }}">
                                        <label for="cargo">Cargo</label>
                                        @if ($errors->has('cargo'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cargo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="input-field">
                                            <input id="password" type="password" name="password" required>
                                            <label for="password">Contraseña</label>
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col s6">
                                    <div class="input-field">
                                        <input id="password-confirm" type="password" name="password_confirmation" required>
                                        <label for="password-confirm">Confirmar Contraseña</label>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <button type="submit" class="btn blue">
                                    Registrar
                                </button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
