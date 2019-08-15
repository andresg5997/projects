@extends('admin.settings.layouts.settings', ['title' => 'Points'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-star"></i> Points for Uploading Media
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('upload_media', $attributes->upload_media, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-star"></i> Points for Media getting a new Like
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('media_get_like', $attributes->media_get_like, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-star"></i> Points for Adding a Comment
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('add_comment', $attributes->add_comment,['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-star"></i> Points for Comment Like
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('comment_get_like', $attributes->comment_get_like, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
