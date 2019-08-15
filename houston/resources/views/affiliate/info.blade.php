@extends('layouts.app', ['title' => 'Affiliate Info'])

@section('styles')
    <link href="{{ url('assets/css/affiliate-tiers.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- Main container -->
    <main>

        @include('components.page_links', ['view' => 'affiliate'])

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
                                <h6>Rules:</h6>
                            </div>

                            <div class="card-block">
                                <p>One of our mottos is to keep everything simple! Hence, do not cheat us and we will have a life long friendship. <i class="fa fa-smile-o"></i></p>
                                <p>We ask you to please follow our <a href="{{ route('page.footer.show', [$tos_parent_slug, 'tos']) }}">terms of service</a> and to not infringe any <a href="{{ route('page.footer.show', [$cp_parent_slug, 'copyright-policy']) }}">DMCA laws</a>.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>How much do I earn?</h6>
                            </div>

                            <div class="card-block">
                                <p>You can see the list below as to how much money you will earn for 10,000 views from each of those countries.</p>
                                <p>Unfortunately, we cannot pay the same amount for an image view than we can for someone who watches a 40 minute video.</p>
                                <p>The list you see below is for videos that are longer than 10 minutes. Anyhow, it is taken as a measure for all media formats.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>What's the commission breakdown?</h6>
                            </div>

                            <div class="card-block">
                                <p><strong>Videos:</strong> Videos that are longer than 10 minutes receive full commission. Videos that are 9 minutes long, receive 90% of the full commission. 8 minute long videos receive 80%, etc..</p>
                                <p><strong>Audio Files:</strong> Audio files receive {{ config('audio_multiplier') * 100 }}% of the commission per 10k views as seen below. Additionally, audio files that are 9 minutes long, receive 90% of the full commission. 8 minute long videos receive 80%, etc...</p>
                                <p><strong>Images:</strong> Images receive {{ config('image_multiplier') * 100 }}% of the commission per 10k views as seen below. Nevertheless, in order to prevent scam from happening, a unique visitor has to view the image for more than {{ config('image_duration_for_commission') }} seconds.</p>
                                <br>
                                <p><strong>For video and audio plays:</strong> In order to receive full commission, the file (video or audio) has to be played (watched or listened to) for around 95-100% of the total duration. If the file is only played 80% of the total duration, your commission is 80% of the total, etc..</p>
                                <p>For anyone that uses any adblocking software, the view/play is only worth 10% of the total value. We understand that this is a "downer", but, unfortunately, we do not make any revenue off of such visits. If you want to maximize your earning potential, you can ask your viewers to turn off any adblocking software and you can also completely block your content. <a href="{{ route('affiliate.settings') }}">Learn how</a>.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>How does the referral system work?</h6>
                            </div>

                            <div class="card-block">
                                <p>Within your affiliate account you have your referral link. You need to send this one to your family or friends and if anyone signs up after having visited your link, you will receive a referral.</p>
                                <p>Once you have referrals start uploading media you will start earning 30% of the total revenue they make.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Payout Info:</h6>
                            </div>

                            <div class="card-block">
                                <p>Once a payment is requested, it will approximately take up to two weeks for you to receive the payment. We will try to payout within a week, after we finished our investigation. We will contact you within 24 hours after you request the payment.</p>
                                <p>Currently, we offer PayPal and Payza as our payout processors. If you wish to see another option, <a href="{{ route('contact.show') }}">contact us</a> and we will discuss it.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>How does Clooud pay us?</h6>
                            </div>

                            <div class="card-block">
                                <p>We work with advertising companies that pay us a certain amount for each advertisement that is viewed, clicked, or played. The majority we forward to our publishers and affiliates, like yourself.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>When will I receive full commission?</h6>
                            </div>

                            <div class="card-block">
                                <p>Simply put: you will receive the commission as demonstrated in the table below for a video file that is longer than 10 minutes and is watched for around 95-100% without detecting any adblocking software.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>Why are my statistics not up to date or off?</h6>
                            </div>

                            <div class="card-block">
                                <p>Please note that statistics only get updated every {{ config('expires_at_interval') }} minutes. If you still believe there is an issue with them, feel free to contact us and we would love to fix it for you.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6>More Questions?</h6>
                            </div>

                            <div class="card-block">
                                <p>We have a continuously evolving <a href="{{ route('page.footer.show', [$tos_parent_slug, 'faq']) }}">FAQ section</a> which is likely to have an answer to your question.</p>
                                <p>If any question is left unanswered, no matter what, please consider contacting us in oder to get your concerns resolved.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        @include('components.affiliate_tiers')

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
@endsection
