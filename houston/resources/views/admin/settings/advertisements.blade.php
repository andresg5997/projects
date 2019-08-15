@extends('admin.settings.layouts.settings', ['title' => 'Advertisements'])
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
                    <p>This will enable you to monetize your web site. Note, you will need to have an advertising/publisher account at, for example, Google Adsense or <a href="http://www.propellerads.com/?rfd=yod">PropellerAds</a>.</p>
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

    <div class="clearfix"></div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <h4 class="box-title">Home Page - Advertisement Settings</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="home_page_ad_slot">
                    <i style="font-size:16px;" class="fa fa-list"></i> Ad replacing a random media item

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>In order to get the best results, either use a responsive ad or a ad with the dimensions of max 380x350px. Note, if it is not a responsive ad, once the grid system changes, the ad's dimensions are set to what is given by you.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('home_page_ad_slot', $attributes->home_page_ad_slot, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <pre>
            <img class="col-md-12" src="{{ url('assets/admin/assets/images/demo-ad-responsive.png') }}">
        </pre>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <h4 class="box-title">Media Index Page - Advertisement Settings</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="media_index_page_main_ad_slot">
                    <i style="font-size:16px;" class="fa fa-list"></i> Main (Leaderboard) Ad

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>In order to retrieve the best results, please consider using a leaderboard format ad.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('media_index_page_main_ad_slot', $attributes->media_index_page_main_ad_slot, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <pre>
            <img class="col-md-12" src="{{ url('assets/admin/assets/images/demo-ad-leaderboard.png') }}">
        </pre>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="media_index_page_sidebar_ad_slot">
                    <i style="font-size:16px;" class="fa fa-list"></i> Sidebar Ad

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>In order to retrieve the best results, please consider using a block format ad.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('media_index_page_sidebar_ad_slot', $attributes->media_index_page_sidebar_ad_slot, ['class' => 'form-control']) !!}
                </div>

            </div>
        </div>

        <pre>
            <img class="col-md-12" src="{{ url('assets/admin/assets/images/demo-ad-sidebar.png') }}">
        </pre>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12" style="margin-bottom: 50px;">
        <hr>
    </div>

    <h4 class="box-title">Embed Page - Advertisement Settings</h4>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="embed_page_interstitial">
                    <i style="font-size:16px;" class="fa fa-list"></i> Embed Page - Interstitial Ad

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>If you consider allowing embedding your media to websites other than yours, please remember that you will not be able to monetize such views, unless you include ads.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('embed_page_interstitial', $attributes->embed_page_interstitial, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <label for="embed_page_pop_under">
                    <i style="font-size:16px;" class="fa fa-list"></i> Embed Page - Pop Under

                </label>
            </div>
            <div class="panel-body">
                <div class="callout callout-info" role="callout">
                    <p>Pop Under ads are a useful tactic monetize your embed page views. A recommended Popunder network is <a href="https://www.popads.net/users/refer/1182213">Popads</a>.</p>
                </div>

                <div class="form-group">
                    {!! Form::textarea('embed_page_pop_under', $attributes->embed_page_pop_under, ['class' => 'form-control']) !!}
                </div>
            </div>

        </div>
    </div>
@endsection
