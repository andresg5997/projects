@extends('admin.settings.layouts.settings', ['title' => 'Analytics'])
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
                    <p>This will enable you to track your visitors activity, behavior and various events using Google Analytics.</p>
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
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Google Analytics ID

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('google_analytics_id', $attributes->google_analytics_id, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
