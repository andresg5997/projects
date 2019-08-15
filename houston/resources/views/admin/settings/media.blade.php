@extends('admin.settings.layouts.settings', ['title' => 'Media'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="general">
                    <i style="font-size:16px;" class="fa fa-info-circle"></i> General
                </label>
            </div>
            <div class="panel-body">
                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="auto_approve" class="js-switch" {{ $attributes->auto_approve ? "checked" : "" }} type="checkbox">
                        Auto approve each post?
                    </label>
                </div>
                <div class="checkbox checkbox-switch" style="margin-top: 15px">
                    <label>
                        <input name="guest_uploads" class="js-switch" {{ $attributes->guest_uploads ? "checked" : "" }} type="checkbox">
                        Allow Guest Uploads?
                    </label>
                </div>
                <div class="checkbox checkbox-switch" style="margin-top: 15px">
                    <label>
                        <input name="remote_uploads" class="js-switch" {{ $attributes->remote_uploads ? "checked" : "" }} type="checkbox">
                        Allow Remote Uploads?
                    </label>
                </div>
                <div class="checkbox checkbox-switch" style="margin-top: 15px">
                    <label>
                        <input name="clone_uploads" class="js-switch" {{ $attributes->clone_uploads ? "checked" : "" }} type="checkbox">
                        Allow Cloning of Uploads?
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
                <label for="media_per_page">
                    <i style="font-size:16px;" class="fa fa-list"></i> Media Per Page
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('media_per_page', $attributes->media_per_page, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="uploads_per_day">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max Uploads per day
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('uploads_per_day', $attributes->uploads_per_day, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="uploads_per_day_per_guest">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max Uploads per Guest (unique visitor who is not signed up) a day
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('uploads_per_day_per_guest', $attributes->uploads_per_day_per_guest, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="delete_after_x_days">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Delete posts/media after "x" days of last viewed
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('delete_after_x_days', $attributes->delete_after_x_days, ['class' => 'form-control']) !!}
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
                <label for="max_file_upload_size_user">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max File Upload Size for Users (in mb)
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('max_file_upload_size_user', $attributes->max_file_upload_size_user, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="max_file_upload_size_guest">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max File Upload Size for Guests (in mb)
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('max_file_upload_size_guest', $attributes->max_file_upload_size_guest, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="max_amount_of_concurrent_uploads_user">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max Amount of Concurrent File Uploads per User
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('max_amount_of_concurrent_uploads_user', $attributes->max_amount_of_concurrent_uploads_user, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="max_amount_of_concurrent_uploads_guest">
                    <i style="font-size:16px;" class="fa fa-upload"></i> Max Amount of Concurrent File Uploads per Guests
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('max_amount_of_concurrent_uploads_guest', $attributes->max_amount_of_concurrent_uploads_guest, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
