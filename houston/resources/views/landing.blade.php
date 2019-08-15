@extends('layouts.app', ['title' => 'Honest Sports', 'noHeader' => true])

@section('content')
    <div class="landing-bg" style="height: 100vh">
        <div class="container d-flex flex-column" style="height: 100%">
            <div class="row" style="justify-self: flex-start; flex: 1;">
                <div class="col-sm-5">
                    <img src="{{ asset('img/logo.png') }}" height="120px">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 text-white">
                    <h1>All your Sports in one hand</h1>
                    <h3>With <i>{{ config('website_title', null) }}</i> you can lead your teams like a professional</h3>
                    <br>
                    @if(!Auth::check())
                        <a href="{{ route('register') }}" class="btn btn-grad">Sign up for free</a>
                    @endif
                    <a href="{{ (\Auth::check()) ? route('teams.index') : route('login') }}" class="btn ml-2 {{ (Auth::check()) ? 'btn-grad' : '' }}">{{ (Auth::check()) ? 'Enter' : 'Login' }}</a>
                </div>
                <div class="col-sm-7" style="height:100%">
                    <img src="{{ asset('img/elements/mockup-macbook.png') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="grad-inverse"></div>

    <div class="container pt-4">
        <header align="center">
            <h2 class="m-0"><b><em>{{ config('website_title', null) }}</em></b></h2>
            <hr class="grad divider-mini opacity-7" style="margin-top: 0px; margin-bottom: 0px;">
            <h3 style="font-family: Kurale">Freedom to coach just like you want</h3>
            <br>
            <p>
                <strong>The best team experience</strong>
                <br>
                <strong>For players, parents, coaches, managers &amp; clubs.</strong>
            </p>
            <p>Our coaching tools are not value-free. We design and develop Honest Sport's tools for teams preferring an <br>atmosphere that is characterized by collaboration and empowerment instead of power and control.<br> We hope that you fell this from the very first moment you start you first team in <strong>{{ config('website_title', null) }}</strong></p>
            <br>
        </header>
    </div>

    <div class="pt-4 features-bg">
        <div class="container">
            <h2 class="m-0 text-white" align="center"><b><em>Features {{ config('website_title', null) }}</em></b></h2>
            <hr class="grad divider-mini opacity-7" style="margin-top: 0px; margin-bottom: 0px;">
            <h3 class="text-white" align="center" style="font-family: Kurale">Everything you need to run your team</h3>
            <br>
            <div class="row mt-1">
                <div class="col-sm-4">
                    <div class="feature-box text-white">
                        <div class="feature-body">
                            <h4>Organise</h4>
                            <div class="divider mb-2"></div>
                            <p>Organise your teams in a heartbeat with powerful and easy tools.</p>
                        </div>
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el1.png') }}" height="100px">
                        </div>
                    </div>
                    <div class="feature-box text-white">
                        <div class="feature-body">
                            <h4>Assignments</h4>
                            <div class="divider mb-2"></div>
                            <p>Make everything clear in the team with assignments system.</p>
                        </div>
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el2.png') }}" height="100px">
                        </div>
                    </div>
                    <div class="feature-box text-white">
                        <div class="feature-body">
                            <h4>Communicate</h4>
                            <div class="divider mb-2"></div>
                            <p>Communicate quickly with your teams with our magic team emails.</p>
                        </div>
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el3.png') }}" height="100px">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <img src="{{ asset('img/elements/mockup-iphone.png') }}" alt="">
                </div>
                <div class="col-sm-4">
                    <div class="feature-box text-white">
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el4.png') }}" height="100px">
                        </div>
                        <div class="feature-body ml-2">
                            <h4>Manage</h4>
                            <div class="divider mb-2"></div>
                            <p>Put your plans in order and leave the rest to Honest Sports.</p>
                        </div>
                    </div>
                    <div class="feature-box text-white">
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el5.png') }}" height="100px">
                        </div>
                        <div class="feature-body ml-2">
                            <h4>Leader</h4>
                            <div class="divider mb-2"></div>
                            <p>Be the guide and let everyone know it.</p>
                        </div>
                    </div>
                    <div class="feature-box text-white">
                        <div class="feature-image">
                            <img src="{{ asset('img/elements/el6.png') }}" height="100px">
                        </div>
                        <div class="feature-body ml-2">
                            <h4>Share</h4>
                            <div class="divider mb-2"></div>
                            <p>Show &amp; share your team victories with everyone.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="grad-inverse m-0">

   {{--  <div class="container" align="center">
        <br>
        <h2 class="m-0"><b><em>Our team</em></b></h2>
        <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
        <h4 style="font-family: Kurale; margin-bottom: 25px; margin-top: 0px">Currently, we're three dreamers and will grow up soon</h4>

        <div class="row equal-blocks">

            <!-- User widget -->
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card user-widget">
                    <div class="card-block text-center">
                        <a href="#"><img src="{{ asset('fill/profile-1.png') }}" alt="Chris"></a>
                        <h5><a href="#">Chris Breuer</a></h5>
                        <p class="lead">Founder</p>
                        <br>
                        <p class="text-justify">Got annoyed by all the annoying websites throwing 20 popups each, so he figured he can do better.</p>
                    </div>

                    <div class="card-footer">
                        <ul class="social-icons">
                            <li><a class="twitter" target="_blank" href="https://twitter.com/ChrisBreuer1904"><i class="fa fa-twitter"></i></a></li>
                            <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/chris-breuer-33231765/"><i class="fa fa-linkedin"></i></a></li>
                            <li><a class="facebook" target="_blank" href="https://www.facebook.com/chrisbreuer93"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <!-- END User widget -->

            <!-- User widget -->
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card user-widget">
                    <div class="card-block text-center">
                        <a href="#"><img src="{{ asset('fill/profile-2.png') }}" alt="Bent"></a>
                        <h5><a href="#">Bent Jenson Jr.</a></h5>
                        <p class="lead">Graphics</p>
                        <br>
                        <p class="text-justify">The reason this website is looking as it does is because of this guy!</p>
                    </div>

                    <div class="card-footer">
                        <ul class="social-icons">
                            <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/bent-jenson-457a5b87/"><i class="fa fa-linkedin"></i></a></li>
                            <li><a class="facebook" target="_blank" href="https://www.facebook.com/bent.j.jenson"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END User widget -->

            <!-- User widget -->
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card user-widget">
                    <div class="card-block text-center">
                        <a href="#"><img src="{{ asset('fill/profile-3.png') }}" alt="Avery"></a>
                        <h5><a href="#">Avery Hill</a></h5>
                        <p class="lead">Marketing</p>
                        <br>
                        <p class="text-justify">In charge of all the marketing related concerns and social media stuff!</p>
                    </div>

                    <div class="card-footer">
                        <ul class="social-icons">
                            <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/avery-hill-4599b4104/"><i class="fa fa-linkedin"></i></a></li>
                            <li><a class="facebook" target="_blank" href="https://www.facebook.com/ave.hill"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END User widget -->
        </div>
    </div> --}}

{{--     <section class="no-border-bottom section-sm dotted-white-bg">

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
    <hr class="grad m-0"> --}}
    {{-- <hr class="grad m-0">
    <div class="newsletter-bg">
        <div class="container">
            <h2 class="m-0 text-white" align="center"><b><em>Subscribe to our newsletter!</em></b></h2>
            <hr class="grad divider-mini opacity-7" style="margin-top: 0px; margin-bottom: 0px;">
            <h3 class="text-white" align="center" style="font-family: Kurale">Your favorite news are here</h3>
            <br><br>
            <div class="row">
                <div class="col-sm-12">
                    {!! Form::open(['method' => 'POST']) !!}
                    <div class="form-group" style="width: 600px; margin: 0 auto; padding-bottom: 30px;">
                        <div class="input-group input-group-lg">
                            <div class="input-group-addon black-bg">
                                <i class="fa fa-envelope fa-lg"></i>
                            </div>
                            <input type="text" class="form-control" placeholder="Send your e-mail">
                            <a class="input-group-addon btn-grad addon-sub" href="#!" style="border:none;">
                                SUBSCRIBE
                            </a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> --}}

    <section class="dotted-white-bg" style="padding: 30px">
        <div class="container">
            <header align="center">
                <h2><strong><i>Get in touch and say Hello!</i></strong></h2>
                <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                <h3 class="kurale">Know more about our benefits.</h3>
            </header>
            <form id="contact-form" method="post" action="{{ route('contact.post.message') }}" class="d-flex mt-4">
                {{ csrf_field() }}
                <div class="flex mr-2">
                    <div class="form-group">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="contact-control">
                    </div>
                    <div class="form-group m-0">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="contact-control">
                    </div>
                </div>
                <div class="flex">
                    <div class="form-group" style="height:100%">
                        <textarea name="message" style="height:100%" class="contact-control" placeholder="Message">{{ old('message') }}</textarea>
                    </div>
                </div>
            </form>
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-black">SEND MESSAGE</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
