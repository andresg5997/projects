@extends('admin.settings.layouts.settings', ['title' => 'Adblock'])
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
                    <p>This can be used to kindly ask the users to disabled their adblocker.</p>
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
                <label for="adblock_notification_message">
                    <i style="font-size:16px;" class="fa fa-envelope-o"></i> Notification Message
                    
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::textarea('notification_message', $attributes->notification_message, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="adblock_popup_time">
                    <i style="font-size:16px;" class="fa fa-clock-o"></i> Popup Time (in seconds)
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('popup_time', $attributes->popup_time, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    
    <pre class="col-md-12">
        <img src="{{ url('assets/admin/assets/images/adblocker-notification.png') }}" class="col-md-12">
    </pre>
    <br>
    <br>
@endsection
