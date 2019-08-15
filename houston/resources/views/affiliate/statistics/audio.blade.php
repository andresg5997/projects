@extends('layouts.app', ['title'=> 'Affiliate Statistics'])

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
                                <h6>Audio Statistics</h6>
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
                                        <td>{{ $this_years_audio_revenue }}</td>
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

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>How much did the viewer listen?</h6>
                            </div>

                            <div class="card-block">
                                {!! $month_chart->render() !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Detailed Statistics</h6>
                            </div>

                            <div class="card-block affiliate-statistics">
                                <table class="table-bordered table-striped table-condensed table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>100%</th>
                                        <th>90%</th>
                                        <th>80%</th>
                                        <th>70%</th>
                                        <th>60%</th>
                                        <th>50%</th>
                                        <th>40%</th>
                                        <th>30%</th>
                                        <th>20%</th>
                                        <th>10%</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="td-title">Today's Views</td>
                                        <td>{{ $todays_percentage_views[100] }}</td>
                                        <td>{{ $todays_percentage_views[90] }}</td>
                                        <td>{{ $todays_percentage_views[80] }}</td>
                                        <td>{{ $todays_percentage_views[70] }}</td>
                                        <td>{{ $todays_percentage_views[60] }}</td>
                                        <td>{{ $todays_percentage_views[50] }}</td>
                                        <td>{{ $todays_percentage_views[40] }}</td>
                                        <td>{{ $todays_percentage_views[30] }}</td>
                                        <td>{{ $todays_percentage_views[20] }}</td>
                                        <td>{{ $todays_percentage_views[10] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">Yesterday's Views</td>
                                        <td>{{ $yesterdays_percentage_views[100] }}</td>
                                        <td>{{ $yesterdays_percentage_views[90] }}</td>
                                        <td>{{ $yesterdays_percentage_views[80] }}</td>
                                        <td>{{ $yesterdays_percentage_views[70] }}</td>
                                        <td>{{ $yesterdays_percentage_views[60] }}</td>
                                        <td>{{ $yesterdays_percentage_views[50] }}</td>
                                        <td>{{ $yesterdays_percentage_views[40] }}</td>
                                        <td>{{ $yesterdays_percentage_views[30] }}</td>
                                        <td>{{ $yesterdays_percentage_views[20] }}</td>
                                        <td>{{ $yesterdays_percentage_views[10] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">Total Views of last 7 days</td>
                                        <td>{{ $last_7_days_percentage_views[100] }}</td>
                                        <td>{{ $last_7_days_percentage_views[90] }}</td>
                                        <td>{{ $last_7_days_percentage_views[80] }}</td>
                                        <td>{{ $last_7_days_percentage_views[70] }}</td>
                                        <td>{{ $last_7_days_percentage_views[60] }}</td>
                                        <td>{{ $last_7_days_percentage_views[50] }}</td>
                                        <td>{{ $last_7_days_percentage_views[40] }}</td>
                                        <td>{{ $last_7_days_percentage_views[30] }}</td>
                                        <td>{{ $last_7_days_percentage_views[20] }}</td>
                                        <td>{{ $last_7_days_percentage_views[10] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">Total Views of last 30 days</td>
                                        <td>{{ $last_30_days_percentage_views[100] }}</td>
                                        <td>{{ $last_30_days_percentage_views[90] }}</td>
                                        <td>{{ $last_30_days_percentage_views[80] }}</td>
                                        <td>{{ $last_30_days_percentage_views[70] }}</td>
                                        <td>{{ $last_30_days_percentage_views[60] }}</td>
                                        <td>{{ $last_30_days_percentage_views[50] }}</td>
                                        <td>{{ $last_30_days_percentage_views[40] }}</td>
                                        <td>{{ $last_30_days_percentage_views[30] }}</td>
                                        <td>{{ $last_30_days_percentage_views[20] }}</td>
                                        <td>{{ $last_30_days_percentage_views[10] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-title">This Years's Views</td>
                                        <td>{{ $this_years_percentage_views[100] }}</td>
                                        <td>{{ $this_years_percentage_views[90] }}</td>
                                        <td>{{ $this_years_percentage_views[80] }}</td>
                                        <td>{{ $this_years_percentage_views[70] }}</td>
                                        <td>{{ $this_years_percentage_views[60] }}</td>
                                        <td>{{ $this_years_percentage_views[50] }}</td>
                                        <td>{{ $this_years_percentage_views[40] }}</td>
                                        <td>{{ $this_years_percentage_views[30] }}</td>
                                        <td>{{ $this_years_percentage_views[20] }}</td>
                                        <td>{{ $this_years_percentage_views[10] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
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
