@extends('admin.settings.layouts.settings', ['title' => 'Comments'])
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
                    <p>This will enable users to comment your media/blog posts.</p>
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
                <label for="disqus">
                    <i style="font-size:16px;" class="fa fa-list"></i> Disqus?

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>In case you would prefer to use Disqus's comment system over Clooud's, make sure you activate comments and paste the disqus code in here.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('disqus', $attributes->disqus, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="slug">
                    <i style="font-size:16px;" class="fa fa-list"></i> Max Comments per minute

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::number('comments_per_minutes', $attributes->comments_per_minutes, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection