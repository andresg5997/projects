@extends('admin.settings.layouts.settings', ['title' => 'Captcha'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="general">
                    <i style="font-size:16px;" class="fa fa-info-circle"></i> General
                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Google's Invisible Recaptcha is used for when users are trying to sign up or use the contact forms.</p>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="active" class="js-switch" {{ $attributes->active ? "checked" : "" }} type="checkbox">
                        Activate?
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="captcha_secret">
                    <i style="font-size:16px;" class="fa fa-lock"></i> Captcha Secret
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('captcha_secret', $attributes->captcha_secret ? decrypt($attributes->captcha_secret) : '', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="captcha_sitekey">
                    <i style="font-size:16px;" class="fa fa-globe"></i> Captcha Sitekey
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('captcha_sitekey', $attributes->captcha_sitekey, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
