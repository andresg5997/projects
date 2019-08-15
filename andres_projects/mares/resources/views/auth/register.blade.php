@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col m8 offset-m2">
            <div class="card">
                <div class="card-content">
                    <div class="container">
                        <span class="card-title">Registrarse </span>
                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="input-field col s12">
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                    <label for="name">Name</label>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="input-field col s12">
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                                    <label for="email">E-Mail Address</label>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="input-field col s12">
                                    <input id="password" type="password" name="password" required>
                                    <label for="password">Password</label>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="input-field col s12">
                                <input id="password-confirm" type="password" name="password_confirmation" required>
                                <label for="password-confirm">Confirm Password</label>
                            </div>
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn blue">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
