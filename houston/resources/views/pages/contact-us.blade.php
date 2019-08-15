@extends('layouts.app', ['title' => 'Contact us'])

@section('content')

<!-- Main container -->
<main>

    <!-- Contact -->
    <section class="dotted-white-bg">
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
    <!-- END Contact -->
</main>
@endsection

@section('scripts')
<script src="{{ asset('vendor/matchHeight/matchHeight.min.js') }}"></script>

<script>
    $('#contact-form').matchHeight(false);
</script>

    @if (config('captcha_active'))
        <script>
            $(".g-recaptcha").addClass("btn btn-primary btn-lg").html("Send");

            function onSubmit(token) {
                $("#contact-form").submit();
                $(".g-recaptcha").prop("disabled", true);
            }
        </script>
    @endif
@endsection
