@extends('layouts.app', ['title' => 'FAQ'])

@section('content')

    <!-- Main container -->
    <main>

        <section class="no-border-bottom section-sm dotted-white-bg">

            <div class="container">

                <header align="center">
                    <h3 style="color: #858585; font-weight: 700"><i>got questions?</i></h3>
                    <h2><strong><i>We got answers!</i></strong></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h3 class="kurale">Keep reading this section to figure out your concerns.</h3>
                </header>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="card" style="padding: 0px 25px">
                            <div class="card-header">
                                <h6>General FAQ</h6>
                            </div>
                            <div class="card-block">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Why another free web app?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>As daily users of media sharing sites ourselves, we got so annoyed by the abundance and ridiculous amount of advertisements that are visualized on many media sharing sites.</p>
                                                <p>We do understand that in order to run a successful business money has to be involved, but we found most commonly used advertising techniques absolutely horrific. Our idea is to provide a friendly, safe and innovative user experience which is not cluttered in ads. Hence, we understand the three priorities: Privacy, effectiveness, and no annoyance through advertising, such as popups.</p>
                                                <p>Those are our priorities and this is where we separate us drastically from any competition. A media playing and sharing service built for speed, simplicity, and security.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How does this app work?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Very easy.</p>
                                                <p>We differentiate ourselves from our competition by the ease and friendliness of use. You can simply sign yourself up for a free account, or not, and upload as many files as you like.</p>
                                                <p>These files can be played and shared with your family and friends and you can gain points and money while doing it!</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse5" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">What can I do with my points?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Points is our competitive measurement system. You gain different amount of points for uploading media, commenting on media, etc. You can compete with and other users to reach a higher score! It is fun.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse7" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How long are my records stored?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>They generally will never get deleted. What we do, though, in order to keep up servers clean and up and running at full speed, we will notify users who have files that have not been visited by anyone for the past 60 days.</p>
                                                <p>In case you would like to keep the file store, just follow the instructions in your email to keep it up.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse8" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Does {{ config('website_title', null) }} take space?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse8" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>No, we do not. We do prioritize premium users to get higher download speeds, though.</p>
                                                <p>What that means is that in case some of our servers are reaching some of their limits during certain parts of the day, we will send the request from specific premium member groups to servers which have more resources available at given time.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse9" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How do I edit my profile?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse9" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>No, we do not. We do prioritize premium users to get higher download speeds, though.</p>
                                                <p>What that means is that in case some of our servers are reaching some of their limits during certain parts of the day, we will send the request from specific premium member groups to servers which have more resources available at given time.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="card" style="padding: 0px 25px">
                            <div class="card-header">
                                <h6>Coaches FAQ</h6>
                            </div>
                            <div class="card-block">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How to add people?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>First off, make sure you enroll in our <a href="{{ route('settings.affiliate') }}">Affiliate Program</a>.</p>
                                                <p>Once you are checked the box and saved the settings, you will gain access to the <a href="{{ route('affiliate.info') }}">Affiliate tab</a> with all of your needed statistics and information.</p>
                                                <p>The moment visitors watch your uploaded videos, gaze at your images and listen to your audio files - that is what you earn money for.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Where are my medias?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Currently, we offer PayPal and Payza as our payout processors. If you wish to see another option, <a href="{{ route('contact.show') }}">contact us</a> and we will discuss it.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse9" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">What can I do with old post?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse9" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Within your affiliate account you have your referral link. You need to send this one to your family or friends and if anyone signs up after having visited your link, you will receive a referral.</p>
                                                <p>Once you have referrals start uploading media you will start earning 30% of the total revenue they make.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse6" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How to notify my players about news?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Please, log in and look at the <a href="{{ route('affiliate.info') }}">affiliate information tab</a>.</p>
                                                <p>Many other questions are answered there and if anything is left unanswered, feel free to <a href="{{ route('contact.show') }}">contact us</a>.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse6" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How do I edit my team's profile?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Please, log in and look at the <a href="{{ route('affiliate.info') }}">affiliate information tab</a>.</p>
                                                <p>Many other questions are answered there and if anything is left unanswered, feel free to <a href="{{ route('contact.show') }}">contact us</a>.</p>
                                            </div>
                                        </div>
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
@section('scripts')
    <script>
        $(function() {
            $(".expand").on( "click", function() {
                // $(this).next().slideToggle(200);
                $expand = $(this).find(">:first-child");

                if($expand.text() == "+") {
                    $expand.text("-");
                } else {
                    $expand.text("+");
                }
            });
        });
    </script>
@endsection
