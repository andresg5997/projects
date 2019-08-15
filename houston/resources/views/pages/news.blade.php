@extends('layouts.app', ['title' => 'News'])

@section('content')

    <!-- Main container -->
    <main>

        <section class="no-border-bottom section-sm dotted-white-bg">

            <header align="center">
                <h2>We got news.</h2>
                <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                <h3 class="kurale">News &amp; blogs for our community</h3>
            </header>

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="news-card">
                            <img src="{{ asset('img/banner.png') }}" alt="">
                            <div class="news-header">
                                <p>
                                    <span class="date">13.08.2017</span> | <span class="author">by Chris</span>
                                </p>
                                <h5 class="news-title">
                                    When Players Need You Most - Good Reminder
                                </h5>
                            </div>
                            <div class="news-body">
                                A few weeks ago I missed an opportunity to be there for one of my players. He was upset about fouling out early and having a tough game &hellip;
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-grad opacity-7 btn-sm">Read more</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="news-card">
                            <img src="{{ asset('img/banner.png') }}" alt="">
                            <div class="news-header">
                                <p>
                                    <span class="date">15.08.2017</span> | <span class="author">by admin</span>
                                </p>
                                <h5 class="news-title">
                                    High Fives - Improve Team Chemistry. Enery and Excitement
                                </h5>
                            </div>
                            <div class="news-body">
                                A very simple way for you to improve your team's chemistry, energy and just have more fun is to encourage more high fives &hellip;
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-grad opacity-7 btn-sm">Read more</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="news-card">
                            <img src="{{ asset('img/banner.png') }}" alt="">
                            <div class="news-header">
                                <p>
                                    <span class="date">20.08.2017</span> | <span class="author">by admin</span>
                                </p>
                                <h5 class="news-title">
                                    How to Decide "IF" or "WHEN" Your Child Should Specialize
                                </h5>
                            </div>
                            <div class="news-body">
                                Here's a good article I found about youth sports specialization. This is highly recommended reading for youth coaches and parents &hellip;
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <button class="btn btn-grad opacity-7 btn-sm">Read more</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>
    <!-- END Main container -->

@endsection
