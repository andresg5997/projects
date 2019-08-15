@extends('admin.settings.layouts.settings', ['title' => 'Email'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="sparkpost_secret">
                    <i style="font-size:16px;" class="fa fa-key"></i> Sparkpost Secret

                </label>
            </div>

            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>This will ensure that your emails are delivered as non-spam emails.</p>
                </div>

                <div class="form-group">
                    {!! Form::text('sparkpost_secret', $attributes->sparkpost_secret ? decrypt($attributes->sparkpost_secret) : '', ['class' => 'form-control']) !!}
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
                <label for="admin_email">
                    <i style="font-size:16px;" class="fa fa-envelope"></i> Admin Email

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::email('admin_email', $attributes->admin_email, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="support_email">
                    <i style="font-size:16px;" class="fa fa-envelope"></i> Support Email

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::email('support_email', $attributes->support_email, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="no_reply_email">
                    <i style="font-size:16px;" class="fa fa-envelope"></i> No-Reply Email

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::email('no_reply_email', $attributes->no_reply_email, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="affiliate_email">
                    <i style="font-size:16px;" class="fa fa-envelope"></i> Affiliate Email

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::email('affiliate_email', $attributes->affiliate_email, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="dmca_email">
                    <i style="font-size:16px;" class="fa fa-envelope"></i> DMCA Email

                </label>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::email('dmca_email', $attributes->dmca_email, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
