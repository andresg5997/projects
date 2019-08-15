@extends('layouts.app', ['title' => 'Premium Plans'])

@section('styles')
    <style>
        .membership-pricing-table {
            width: 100%
        }

        .membership-pricing-table table .icon-no,.membership-pricing-table table .icon-yes {
            font-size: 22px
        }

        .membership-pricing-table table .icon-no {
            color: #a93717
        }

        .membership-pricing-table table .icon-yes {
            color: #209e61
        }

        .membership-pricing-table table .plan-header {
            text-align: center;
            font-size: 48px;
            border: 1px solid #e2e2e2;
            padding: 25px 0
        }

        .membership-pricing-table table .plan-header-free {
            background-color: #eee;
            color: #555
        }

        .membership-pricing-table table .plan-header-blue {
            color: #fff;
            background-color: #61a1d1;
            border-color: #3989c6
        }

        .membership-pricing-table table .plan-header-standard {
            color: #fff;
            background-color: white;
            /*border-color: #e37900*/
        }

        .membership-pricing-table table td {
            text-align: center;
            width: 15%;
            padding: 7px 10px;
            background-color: #fafafa;
            font-size: 14px;
            -webkit-box-shadow: 0 1px 0 #fff inset;
            box-shadow: 0 1px 0 #fff inset
        }

        .membership-pricing-table table,.membership-pricing-table table td {
            border: 1px solid #ebebeb
        }

        .membership-pricing-table table tr td:first-child {
            background-color: transparent;
            text-align: right;
            width: 24%
        }

        .membership-pricing-table table tr td:nth-child(5) {
            background-color: #FFF
        }

        .membership-pricing-table table tr:first-child td,.membership-pricing-table table tr:nth-child(2) td {
            -webkit-box-shadow: none;
            box-shadow: none
        }

        .membership-pricing-table table tr:first-child th:first-child {
            border-top-color: transparent;
            border-left-color: transparent;
            border-right-color: #e2e2e2
        }

        .membership-pricing-table table tr:first-child th .pricing-plan-name {
            font-size: 22px
        }

        .membership-pricing-table table tr:first-child th .pricing-plan-price {
            line-height: 35px
        }

        .membership-pricing-table table tr:first-child th .pricing-plan-price>sup {
            font-size: 45%
        }

        .membership-pricing-table table tr:first-child th .pricing-plan-price>span {
            font-size: 30%
        }

        .membership-pricing-table table tr:first-child th .pricing-plan-period {
            margin-top: -7px;
            font-size: 25%
        }

        .membership-pricing-table table .header-plan-inner {
            position: relative
        }

        .membership-pricing-table table .recommended-plan-ribbon {
            box-sizing: content-box;
            background-color: #dc3b5d;
            color: #FFF;
            position: absolute;
            padding: 3px 6px;
            font-size: 11px!important;
            font-weight: 500;
            left: -6px;
            top: -22px;
            z-index: 99;
            width: 100%;
            -webkit-box-shadow: 0 -1px #c2284c inset;
            box-shadow: 0 -1px #c2284c inset;
            text-shadow: 0 -1px #c2284c
        }

        .membership-pricing-table table .recommended-plan-ribbon:before {
            border: solid;
            border-color: #c2284c transparent;
            border-width: 6px 0 0 6px;
            bottom: -5px;
            content: "";
            left: 0;
            position: absolute;
            z-index: 90
        }

        .membership-pricing-table table .recommended-plan-ribbon:after {
            border: solid;
            border-color: #c2284c transparent;
            border-width: 6px 6px 0 0;
            bottom: -5px;
            content: "";
            right: 0;
            position: absolute;
            z-index: 90
        }

        .membership-pricing-table table .plan-head {
            box-sizing: content-box;
            background-color: #ff9c00;
            border: 1px solid #cf7300;
            position: absolute;
            top: -33px;
            left: -1px;
            height: 30px;
            width: 100%;
            border-bottom: none
        }
    </style>
@endsection
@section('content')

    <!-- Main container -->
    <main>

        <!-- Premium Plans -->
        <section class="dotted-white-bg">
            <div class="container">
                <header align="center">
                    <h2><strong><i>Subscription Plans</i></strong></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h3 class="kurale">Know more about our benefits.</h3>
                </header>
                <center>
                    <div class="card">
                        <div class="table-responsive">
                            <div class="membership-pricing-table">
                                <table>
                                    <tbody><tr>
                                        <th class="plan-header plan-header-standard">
                                            <div class="pricing-plan-name">FREE</div>
                                            <div class="pricing-plan-price">
                                                <sup>$</sup>0<span>.00</span>
                                            </div>
                                            <div class="pricing-plan-period"></div>
                                        </th>
                                        <th class="plan-header plan-header-standard">
                                            <div class="pricing-plan-name">Weekly</div>
                                            <div class="pricing-plan-price">
                                                <sup>$</sup>2<span>.99</span>
                                            </div>
                                            <div class="pricing-plan-period"></div>
                                        </th>
                                        <th class="plan-header plan-header-standard">
                                            <div class="pricing-plan-name">Monthly</div>
                                            <div class="pricing-plan-price">
                                                <sup>$</sup>5<span>.99</span>
                                            </div>
                                            <div class="pricing-plan-period"></div>
                                        </th>
                                        <th class="plan-header plan-header-standard">
                                            <div class="header-plan-inner">
                                                <!--<span class="plan-head"> </span>-->
                                                <span class="recommended-plan-ribbon">RECOMMENDED</span>
                                                <div class="pricing-plan-name">3 Monthly</div>
                                                <div class="pricing-plan-price">
                                                    <sup>$</sup>9<span>.99</span>
                                                </div>
                                                <div class="pricing-plan-period"></div>
                                            </div>
                                        </th>
                                        <th class="plan-header plan-header-standard">
                                            <div class="pricing-plan-name">Yearly</div>
                                            <div class="pricing-plan-price">
                                                <sup>$</sup>29<span>.99</span>
                                            </div>
                                            <div class="pricing-plan-period"></div>
                                        </th>
                                    </tr>
                                     <tr>
                                        <td>Advertisement Free: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Advertisement Free: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Advertisement Free: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Advertisement Free: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Advertisement Free: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Prioritized Speed: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Prioritized Speed: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Prioritized Speed: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Prioritized Speed: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Prioritized Speed: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Files Kept Forever: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Files Kept Forever: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Files Kept Forever: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Files Kept Forever: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Files Kept Forever: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Pairing Without Expiry: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Pairing Without Expiry: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Pairing Without Expiry: <span class="icon-no fa fa-times-circle"></span></td>
                                        <td>Pairing Without Expiry: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                        <td>Pairing Without Expiry: <span class="icon-yes fa fa-check-circle-o"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Size Upload Limit: 1Gb</td>
                                        <td>Size Upload Limit: 2Gb</td>
                                        <td>Size Upload Limit: 3Gb</td>
                                        <td>Size Upload Limit: 4Gb</td>
                                        <td>Size Upload Limit: 5Gb</td>
                                    </tr>
                                    @if(Auth::check())
                                        <tr>
                                            <td class="action-header">
                                                <a class="btn btn-sm btn-grad opacity-7">
                                                    PURCHASE
                                                </a>
                                            </td>
                                            <td class="action-header">
                                                <a class="btn btn-sm btn-grad opacity-7">
                                                    PURCHASE
                                                </a>
                                            </td>
                                            <td class="action-header">
                                                <a class="btn btn-sm btn-grad opacity-7">
                                                    PURCHASE
                                                </a>
                                            </td>
                                            <td class="action-header">
                                                <a class="btn btn-sm btn-grad opacity-7">
                                                    PURCHASE
                                                </a>
                                            </td>
                                            <td class="action-header">
                                                <div class="current-plan">
                                                    <div class="with-date"><a href="#!" class="btn btn-sm btn-grad opacity-7">PURCHASE</a></div>
                                                    <!--<div><em class="smaller block">renews</em></div>-->
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </center>
            </div>
        </section>
        <!-- END Premium Plans -->

    </main>
    <!-- END Main container -->

@endsection
