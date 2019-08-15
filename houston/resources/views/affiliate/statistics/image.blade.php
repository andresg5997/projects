@extends('layouts.app', ['title'=> 'Image Statistics'])

@section('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <link href="{{ url('assets/css/affiliate.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- Main container -->
    <main>

        @include('components.page_links', ['view' => 'affiliate', 'expires_at_interval' => $expires_at_interval])

        <section class="no-border-bottom section-sm">
            <div class="container">
                @if (session()->has('flash_notification.message'))
                    <div class="header">
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                            {!! session('flash_notification.message') !!}
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Image Statistics</h6>
                            </div>

                            <div class="card-block affiliate-statistics">
                                <table class="table-bordered table-striped table-condensed table">
                                    <tbody>
                                    <tr>
                                        <td class="title">Today's Views</td>
                                        <td>{{ $todays_views }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Yesterday's Views</td>
                                        <td>{{ $yesterdays_views }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Total Views of last 7 days</td>
                                        <td>{{ $last_7_days_views }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Total Views of last 30 days</td>
                                        <td>{{ $last_30_days_views }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">This Years's Views</td>
                                        <td>{{ $this_years_views }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table-bordered table-striped table-condensed table">
                                    <tbody>
                                    <tr>
                                        <td class="title">Today's Revenue</td>
                                        <td>{{ $todays_revenue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Yesterday's Revenue</td>
                                        <td>{{ $yesterdays_revenue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Last 7 Day's Revenue</td>
                                        <td>{{ $last_7_days_revenue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">Last 30 Day's Revenue</td>
                                        <td>{{ $last_30_days_revenue }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">This Years's Revenue</td>
                                        <td>{{ $this_years_image_revenue }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p class="small">* All revenue amounts shown are USD ($) amounts. </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Today vs Yesterday</h6>
                            </div>

                            <div class="card-block">
                                {!! $line_chart->render() !!}
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6>Adblock Usage</h6>
                            </div>

                            <div class="card-block">
                                <p>{{ $adblock_percentage }}% of your viewers use Adblocking software. <a href="{{ route("affiliate.settings") }}">Ask them to turn it off.</a></p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Last 7 Days's Views</h6>
                            </div>

                            <div class="card-block">
                                {!! $bar_chart->render() !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Last 7 Days's Revenue</h6>
                            </div>

                            <div class="card-block">
                                {!! $revenue_chart->render() !!}
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </section>

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
@endsection
