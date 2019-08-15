@extends('admin.settings.layouts.settings', ['title' => 'General'])
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
                    <p>A local environment means that you will receive a descriptive error output in the browser. Some "packages" also are only enabled on local environments for testing and debugging purposes.</p>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        Local Environment? &nbsp; &nbsp; &nbsp; &nbsp;
                        <input name="active" class="js-switch" {{ $attributes->local_environment ? "checked" : "" }} type="checkbox">
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <hr>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Website Title
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('website_title', $attributes->website_title, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slogan">
                    <i style="font-size:16px;" class="fa fa-list"></i> Website Slogan
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('main_title', $attributes->main_title, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Website Description
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('website_desc', $attributes->website_desc, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Website Keywords
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('website_keywords', $attributes->website_keywords, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <h4 class="box-title">Website Logos</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Header Logo (recommended height 128px)
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::file('logo', null) !!}
                </div>
                <img class="col-md-6" src="{{ url('assets/images/logo.png') }}">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Media Player Brand Icon
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::file('brand', null) !!}
                </div>
                <img class="col-md-6" src="{{ url('assets/images/brand.png') }}">
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Favicon
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::file('favicon', null) !!}
                </div>
                <img src="{{ url('assets/images/brand.png') }}">
            </div>
        </div>
    </div>
@endsection
