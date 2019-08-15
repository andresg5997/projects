@extends('admin.settings.layouts.settings', ['title' => 'Social Links'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="facebook">
                    <i style="font-size:16px;" class="fa fa-facebook"></i> Facebook
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('facebook', $attributes->facebook, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="twitter">
                    <i style="font-size:16px;" class="fa fa-twitter"></i> Twitter
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('twitter', $attributes->twitter, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="instagram">
                    <i style="font-size:16px;" class="fa fa-instagram"></i> Instagram
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('instagram', $attributes->instagram, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
