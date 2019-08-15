@extends('layouts.app', ['title' => 'About Us'])

@section('content')

    <!-- Main container -->
    <main>

        <!-- Team -->
        <section class="no-border-bottom dotted-white-bg">
            <div class="container">
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
                    <h2 class="m-0"><b><em>Our team</em></b></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h4 style="font-family: Kurale; margin-bottom: 25px; margin-top: 0px">Currently, we're three dreamers and will grow up soon</h4>
                </header>

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

            </div>
        </section>
        <!-- END Team -->
    </main>
    <!-- END Main container -->

@endsection
