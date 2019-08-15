@extends('admin.settings.layouts.settings', ['title' => 'Storage'])
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
                    <p>There are currently 3 storage options. You can either store and display all your files from your local drive or you can use Amazon S3's or Dropbox's storage.</p>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="s3_active" class="js-switch" {{ $attributes->s3_active ? "checked" : "" }} type="checkbox">
                        Use Amazon S3 to host your files?
                    </label>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="dropbox_active" class="js-switch" {{ $attributes->dropbox_active ? "checked" : "" }} type="checkbox">
                        Use Dropbox to store your files?
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="general">
                    <i style="font-size:16px;" class="fa fa-info-circle"></i> Local Copy
                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>If you choose to store your files on AWS, you can select whether you would also like to keep the files stored locally as well.</p>
                </div>

                <div class="checkbox checkbox-switch">
                    <label>
                        <input name="keep_copy" class="js-switch" {{ $attributes->keep_copy ? "checked" : "" }} type="checkbox">
                        Keep a local copy of your files?
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <hr>

    <h4 class="box-title"><i class="fa fa-amazon"></i> Amazon S3 Configuration</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="s3_key">
                    <i style="font-size:16px;" class="fa fa-key"></i> Key
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('s3_key', $attributes->s3_key, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="s3_secret">
                    <i style="font-size:16px;" class="fa fa-user-secret"></i> Secret
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('s3_secret', $attributes->s3_secret ? decrypt($attributes->s3_secret) : '', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="s3_region">
                    <i style="font-size:16px;" class="fa fa-globe"></i> Region
                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Available region names can be found <a href="http://docs.aws.amazon.com/general/latest/gr/rande.html">here</a>.</p>
                </div>

                <div class="form-group">
                    {!! Form::text('s3_region', $attributes->s3_region, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="s3_bucket">
                    <i style="font-size:16px;" class="fa fa-hdd-o"></i> Bucket
                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::text('s3_bucket', $attributes->s3_bucket, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <hr>

    <h4 class="box-title"><i class="fa fa-dropbox"></i> Dropbox Configuration</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="dropbox_authorization_token">
                    <i style="font-size:16px;" class="fa fa-user-secret"></i> Authorization Token
                </label>
            </div>

            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>A token can be generated in the <a href="https://www.dropbox.com/developers/apps">App Console</a> for any Dropbox API app.</p>
                </div>

                <div class="callout callout-warning" role="callout">
                    <p>At the moment you can only store files at on Dropbox and they cannot be viewed from there, so make sure you keep a local copy so your visitors can see the uploaded media.</p>
                </div>

                <div class="form-group">
                    {!! Form::text('dropbox_authorization_token', $attributes->dropbox_authorization_token ? decrypt($attributes->dropbox_authorization_token) : '', ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
