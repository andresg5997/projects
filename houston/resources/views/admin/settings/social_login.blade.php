@extends('admin.settings.layouts.settings', ['title' => 'Social Login'])
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
                    <p>This will enable your users to login via Social Media.</p>
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
                <label for="facebook_client_id">
                    <i style="font-size:16px;" class="fa fa-facebook"></i>
                    Facebook Client ID
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('facebook_client_id', $attributes->facebook_client_id, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="facebook_client_secret">
                    <i style="font-size:16px;" class="fa fa-facebook"></i>
                    Facebook Client Secret
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('facebook_client_secret', $attributes->facebook_client_secret ? decrypt($attributes->facebook_client_secret) : '', ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="twitter_client_id">
                    <i style="font-size:16px;" class="fa fa-twitter"></i>
                    Twitter Client ID
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('twitter_client_id', $attributes->twitter_client_id, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="twitter_client_secret">
                    <i style="font-size:16px;" class="fa fa-twitter"></i>
                    Twitter Client Secret
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('twitter_client_secret', $attributes->twitter_client_secret ? decrypt($attributes->twitter_client_secret) : '', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
