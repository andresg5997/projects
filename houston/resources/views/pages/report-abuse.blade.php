@extends('layouts.app', ['title' => 'DMCA Form'])

@section('content')

    <!-- Main container -->
    <main>

        <!-- DMCA Form -->
        <section class="dotted-white-bg">
            <div class="container">
                <header align="center">
                    <h2><strong><i>Report any problem here!</i></strong></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h3 class="kurale">Let's fight these issues together.</h3>
                </header>

                <div class="card col-sm-12 col-md-10 col-md-offset-1">
                    <div class="card-header">
                        <h6>Write us a message</h6>
                    </div>

                    @include('components.error_notification')

                    @include('components.flash_notification')

                    <div class="card-block">
                        <form id="dmca-form" method="post" action="{{ route('dmca.post.message') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <h3>I am...</h3>
                                    <div class="form-group">
                                        <div style="margin-left: 20px">
                                            <label><input type="radio" name="is-owner" value="1"> the owner of the infringing content</label>
                                            <label><input type="radio" name="is-owner" value="2"> an agent, commissioned and allowed to file this takedown notice on behalf of the rightholder</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Your Full Legal Name</label>
                                        <input type="text" id="name" class="report-control" name="name" value="{{ old('name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email</label>
                                        <input type="email" id="email" class="report-control" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="address">Address</label>
                                        <input type="text" id="address" class="report-control" name="address" value="{{ old('address') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="zip">Zip/Postal Code</label>
                                        <input type="text" id="zip" class="report-control" name="zip" value="{{ old('zip') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="country">Country</label>
                                        <input type="text" id="country" class="report-control" name="country" value="{{ old('country') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <input type="text" id="city" class="report-control" name="city" value="{{ old('city') }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label" for="rightsholder-name">Rightsholder Full Legal Name (if you are not the owner)</label>
                                        <input type="text" id="rightsholder-name" class="report-control" name="rightsholder-name" value="{{ old('rightsholder-name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="rightsholder-country">Rightsholder Country (if you are not the owner)</label>
                                        <input type="text" id="rightsholder-country" class="report-control" name="rightsholder-country" value="{{ old('rightsholder-country') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="company">Your Company/Organisation</label>
                                        <input type="text" id="company" class="report-control" name="company" value="{{ old('company') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="phone">Phone</label>
                                        <input type="text" id="phone" class="report-control" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="job">Your Job Title / Position</label>
                                        <input type="text" id="job" class="report-control" name="job" value="{{ old('job') }}">
                                    </div>
                                </div>

                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label" for="infringing-urls">URL(s) of infringing content</label>
                                        <textarea id="infringing-urls" class="report-control" name="infringing-urls" rows="6" value="{{ old('infringing-urls') }}"></textarea>
                                    </div>
                                    <h3 class="footer-title">By checking the following box, I state that:</h3>
                                    <p><b>
                                        I am the owner, or an agent authorized to act on behalf of the owner of an exclusive right that is allegedly infringed.
                                        I have good faith belief that the use of the content in the manner complained of, is not authorized by the copyright owner, its agent, or the law.
                                        The information in this notification is accurate. This also means that each content type field contains corresponding information related to a single copyright infringement only. Otherwise it is possible that the notification is not going to be processed.
                                        I acknowledge that there may be adverse legal consequences for making false or bad faith allegations of copyright infringement by using this process.
                                        I understand that each affected user will be informed about this take down action. This also includes contact details you provide, upon the user's request.
                                    </b></p>
                                    <div class="form-group" style="margin-top: 15px">
                                        <input type="checkbox" id="confirm" name="confirm">
                                        <label for="confirm"> I read the paragraphs above and confirm that they are 100% true in this case</label>
                                    </div>
                                    @if (config('captch_active'))
                                        {!! app('captcha')->display($attributes = ['data-badge' => 'bottomleft', 'data-callback' => 'onSubmit']) !!}
                                    @else
                                        <div class="form-group" align="center">
                                            <button type="submit" onclick="prop('disabled', true);" class="btn btn-black btn-lg">Send message</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </section>
        <!-- END DMCA Form -->

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
    @if (config('captch_active'))
        <script>
            $(".g-recaptcha").addClass("btn btn-primary btn-lg").html("Submit");

            function onSubmit(token) {
                $("#dmca-form").submit();
                $(".g-recaptcha").prop("disabled", true);
            }
        </script>
    @endif
@endsection
