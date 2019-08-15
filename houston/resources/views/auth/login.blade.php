@extends('layouts.app', ['title' => 'Login'])

@section('content')

<!-- Main container -->
<main>
    <section class="no-border-bottom section-sm login-screen">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">
                    @if (config('social_keys_active'))
                        <div class="card login-header">
                            <div class="card-block">
                                <h6 class="text-uppercase no-margin-top" align="center">Login</h6>
                                {{-- <div class="row">
                                        <div class="col-xs-6">
                                            <a class="btn btn-facebook btn-sm btn-block" href="{{ route('redirect','facebook') }}"><i class="fa fa-facebook"></i> Facebook</a>
                                        </div>

                                        <div class="col-xs-6">
                                            <a class="btn btn-twitter btn-sm btn-block" href="{{route('redirect','twitter')}}"><i class="fa fa-twitter"></i> Twitter</a>
                                        </div>
                                </div> --}}
                            </div>
                        </div>
                    @endif

                    <div class="card login-body">

                        <div class="card-block login-body-inner">
                            <h6 align="center">Sign in</h6>
                            <br>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="input-group login-group">
                                        <div class="login-icon input-group-addon addon-transparent">
                                            <span class="fa fa-user"></span>
                                        </div>
                                        <input class="form-control login-input input-lg bl-0" type="email" name="email" placeholder="Email" required autofocus>
                                    </div>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                </div>


                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="login-group input-group">
                                        <div class="login-icon input-group-addon addon-transparent">
                                            <span class="fa fa-lock"></span>
                                        </div>
                                        <input class="form-control login-input input-lg bl-0" name="password" type="password" placeholder="Password" required>
                                    </div>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                </div>

                                <button class="btn login-submit btn-block" type="submit">Login</button>
                            </form>
                            <br>
                            <div class="row text-center">
                                <div class="col-xs-6">
                                    <a href="{{ url('register') }}">Register</a>
                                </div>

                                <div class="col-xs-6">
                                    <a href="{{ url('password/reset') }}">Forget password?</a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

</main>
<!-- END Main container -->

@endsection
