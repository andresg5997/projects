@extends('layouts.app', ['title' => 'Profile Settings'])

@section('styles')
    <link rel="stylesheet" href="{{ url('assets/css/switchery.min.css') }}">
@endsection

@section('content')
    <!-- Main container -->
    <main>

        @include('components.page_links', ['view' => 'affiliate'])

        <section class="no-border-bottom section-sm">
            <div class="container">

                @include('components.flash_notification')

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>{{ $page }}</h6>
                            </div>

                            <div class="card-block">
                                <p>Adblock settings:</p>
                                <form class="form-horizontal" method="post" action="{{ route('affiliate.settings.update') }}">
                                    {{ method_field('patch') }}
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="checkbox checkbox-switch">
                                            <label>
                                                <input name="adblock-ask" class="js-switch" {{ ($status_ask) ? "checked" : "" }} type="checkbox">
                                                <span>If Adblocking software is detected, ask the visitor to turn it off.</span>
                                            </label>
                                        </div>

                                        <div class="checkbox checkbox-switch">
                                            <label>
                                                <input name="adblock-off" class="js-switch" {{ ($status_off) ? "checked" : "" }} type="checkbox">
                                                <span>Do not allow visitors to view your content, unless their Adblocking software is turned off.</span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                        </div>
                                    </div>

                                </form>

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
    <script src="{{ url('assets/js/switchery.min.js') }}"></script>
@endsection
