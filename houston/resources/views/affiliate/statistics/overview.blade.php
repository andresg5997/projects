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
                    <div class="col-xs-10 col-sm-10 col-xs-offset-1 col-sm-offset-1">
                        <div class="card">
                            <div class="card-header">
                                <h6>Overview</h6>
                            </div>

                            <div class="card-block affiliate-statistics">
                                <table class="table-bordered table-striped table-condensed table" style="width: 46% ">
                                    <tbody>
                                    <tr>
                                        <td class="title_overview">Current Account Balance</td>
                                        <td class="item_overview">{{ $current_account_balance }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">All Time Earnings</td>
                                        <td class="item_overview">{{ $all_time_account_balance }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table class="table-bordered table-striped table-condensed table">
                                    <thead>
                                    <th>#</th>
                                    <th>Total</th>
                                    <th>Audio</th>
                                    <th>Image</th>
                                    <th>Video</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="title_overview">Today's Views</td>
                                        <td class="item_overview">{{ $total_todays_views }}</td>
                                        <td class="item_overview">{{ $audio_stats["todays_views"] }}</td>
                                        <td class="item_overview">{{ $image_stats["todays_views"] }}</td>
                                        <td class="item_overview">{{ $video_stats["todays_views"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Yesterday's Views</td>
                                        <td class="item_overview">{{ $total_yesterdays_views }}</td>
                                        <td class="item_overview">{{ $audio_stats["yesterdays_views"] }}</td>
                                        <td class="item_overview">{{ $image_stats["yesterdays_views"] }}</td>
                                        <td class="item_overview">{{ $video_stats["yesterdays_views"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Total Views of last 7 days</td>
                                        <td class="item_overview">{{ $total_last_7_days_views }}</td>
                                        <td class="item_overview">{{ $audio_stats["last_7_days_views"] }}</td>
                                        <td class="item_overview">{{ $image_stats["last_7_days_views"] }}</td>
                                        <td class="item_overview">{{ $video_stats["last_7_days_views"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Total Views of last 30 days</td>
                                        <td class="item_overview">{{ $total_last_30_days_views }}</td>
                                        <td class="item_overview">{{ $audio_stats["last_30_days_views"] }}</td>
                                        <td class="item_overview">{{ $image_stats["last_30_days_views"] }}</td>
                                        <td class="item_overview">{{ $video_stats["last_30_days_views"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">This Years's Views</td>
                                        <td class="item_overview">{{ $total_views_this_year }}</td>
                                        <td class="item_overview">{{ $audio_stats["this_years_views"] }}</td>
                                        <td class="item_overview">{{ $image_stats["this_years_views"] }}</td>
                                        <td class="item_overview">{{ $video_stats["this_years_views"] }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table class="table-bordered table-striped table-condensed table">
                                    <tbody>
                                    <tr>
                                        <td class="title_overview">Today's Revenue</td>
                                        <td class="item_overview">{{ $total_todays_revenue }}</td>
                                        <td class="item_overview">{{ $audio_stats["todays_revenue"] }}</td>
                                        <td class="item_overview">{{ $image_stats["todays_revenue"] }}</td>
                                        <td class="item_overview">{{ $video_stats["todays_revenue"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Yesterday's Revenue</td>
                                        <td class="item_overview">{{ $total_yesterdays_revenue }}</td>
                                        <td class="item_overview">{{ $audio_stats["yesterdays_revenue"] }}</td>
                                        <td class="item_overview">{{ $image_stats["yesterdays_revenue"] }}</td>
                                        <td class="item_overview">{{ $video_stats["yesterdays_revenue"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Last 7 Day's Revenue</td>
                                        <td class="item_overview">{{ $total_last_7_days_revenue }}</td>
                                        <td class="item_overview">{{ $audio_stats["last_7_days_revenue"] }}</td>
                                        <td class="item_overview">{{ $image_stats["last_7_days_revenue"] }}</td>
                                        <td class="item_overview">{{ $video_stats["last_7_days_revenue"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">Last 30 Day's Revenue</td>
                                        <td class="item_overview">{{ $total_last_30_days_revenue }}</td>
                                        <td class="item_overview">{{ $audio_stats["last_30_days_revenue"] }}</td>
                                        <td class="item_overview">{{ $image_stats["last_30_days_revenue"] }}</td>
                                        <td class="item_overview">{{ $video_stats["last_30_days_revenue"] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="title_overview">This Years's Revenue</td>
                                        <td class="item_overview">{{ $total_revenue_this_year }}</td>
                                        <td class="item_overview">{{ $audio_stats["this_years_revenue"] }}</td>
                                        <td class="item_overview">{{ $image_stats["this_years_revenue"] }}</td>
                                        <td class="item_overview">{{ $video_stats["this_years_revenue"] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p class="small">* All amounts shown are USD ($) amounts. </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-5 col-sm-5 col-xs-offset-1 col-sm-offset-1">
                        <div class="card">
                            <div class="card-header">
                                <h6>Referral ID</h6>
                            </div>

                            <div class="card-block">
                                <input style="width: 100%; margin-bottom: 20px" type="text" readonly="readonly" value="{{ url('/').'/?ref=' . Auth::user()->affiliate_id }}">
                                <p class="small">By using the referral code you can earn money by referring your friends!<p>
                                <p class="small"><a href="{{ route('page.footer.show', [$faq_parent_slug, 'faq']) }}">Read more...</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-5 col-sm-5">
                        <div class="card">
                            <div class="card-header">
                                <h6>Referrals</h6>
                            </div>

                            <div class="card-block affiliate-statistics">
                                @if(count($referrals) > 0)
                                    <table class="table-bordered table-striped table-condensed table">
                                        <thead>
                                        <th>Name</th>
                                        <th>Balance*</th>
                                        </thead>
                                        <tbody>
                                        @foreach($referrals as $referral)
                                            <tr>
                                                <td class=""><a href="{{ url('user/' . $referral['username']) }}">{{ $referral['username'] }}</a></td>
                                                <td class="">{{ $referral['all_time_account_balance'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <table class="table-bordered table-striped table-condensed table">
                                        <thead>
                                        <th class="">Total Amount</th>
                                        <th class="">{{ $total_referral_amount }}</th>
                                        </thead>
                                    </table>
                                    <p class="small">* The balance represents the total accumulated all time balance.</p>
                                @else
                                    <p>You currently have no referrals.</p>
                                @endif
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
