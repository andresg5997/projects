@extends('admin.settings.layouts.settings', ['title' => 'Cache'])
@section('settings-content')
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="expire_at_interval">
                    <i style="font-size:16px;" class="fa fa-star"></i> Expire Interval for Caching
                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Clooud uses Caching techniques to improve page speed and load time. We cache the home page queries and affiliate statistic queries. Choose the expire interval when the cache needs to reset.</p>
                    <p>If you prefer to have everything displayed in real time, feel free to set the interval to "0".</p>
                </div>

                <div class="form-group">
                    {!! Form::number('expires_at_interval', $attributes->expires_at_interval, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
