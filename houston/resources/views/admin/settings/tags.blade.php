@extends('admin.settings.layouts.settings', ['title' => 'Tags'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="max_tags_per_media">
                    <i style="font-size:16px;" class="fa fa-list"></i> Max Tags per Post
                </label>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('max_tags_per_media', $attributes->max_tags_per_media, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
