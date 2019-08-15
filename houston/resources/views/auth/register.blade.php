@extends('layouts.app', ['title' => 'Register'])

@section('content')

<!-- Main container -->
<main>

    <section class="no-border-bottom section-sm login-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-6 col-lg-offset-3">

                    @if (config('social_keys_active'))
                        <div class="card login-header">
                            <div class="card-block">
                                <h6 class="text-uppercase no-margin-top" align="center">Register</h6>

{{--                                 <div class="row">
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
                            <h6 align="center">Sign up</h6>
                            <br>
                            @include('components.error_notification')
                            <br>
                            <form id="register-form" class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input class="form-control login-input input-lg" type="text" name="username" placeholder="Username" value="{{ old('username') }}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control login-input input-lg" type="text" name="first_name" placeholder="{{ trans('settings.first_name') }}" value="{{ old('first_name') }}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control login-input input-lg" type="text" name="last_name" placeholder="{{ trans('settings.last_name') }}" value="{{ old('last_name') }}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control login-input input-lg" name="email" type="text" placeholder="Email address" value="{{ old('email') }}">
                                </div>

                                <div class="form-group">
                                    <input class="form-control login-input input-lg" name="password" type="password" placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <input class="form-control login-input input-lg" name="password_confirmation" type="password" placeholder="Password confirmation">
                                </div>


                                <p class="small text-muted" style="margin-top: 10px">You're accepting our <a href="{{ route('page.footer.show', [$tos_parent_slug, 'tos']) }}">terms and conditions</a> by clicking on following button.</p>
                                <br>
                                @if (config('captcha_active'))
                                    {!! app('captcha')->display($attributes = ['data-badge' => 'bottomleft', 'data-callback' => 'onSubmit']) !!}
                                @else
                                    <button class="btn login-submit btn-block" type="submit">Register</button>
                                @endif
                            </form>
                        </div>
                        <div class="card-footer">
                            <a href="{{ url('login') }}">Already a member?</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
<!-- END Main container -->
@endsection

@section('scripts')
    @if (config('captcha_active'))
        <script>
            $(".g-recaptcha").addClass("btn btn-primary btn-block").html("Register");

            function onSubmit(token) {
                $("#register-form").submit();
            }
        </script>
    @endif
@endsection
