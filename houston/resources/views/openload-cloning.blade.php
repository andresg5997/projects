@extends('layouts.app', ['title' => 'Bulk Upload'])

@section('content')

<!-- Main container -->
<main>

    <!-- Contact -->
    <section class="bg-white">
        <div class="container">
            <header class="section-header">
                <h2>Openload Cloning</h2>
            </header>
            <div class="card col-sm-12 col-md-8 col-md-offset-2">
                <div class="card-header">
                    <h6>Upload links from text files</h6>
                </div>

                @include('components.error_notification')

                @include('components.flash_notification')

                <div class="card-block">
                    <form id="openload-form" method="post" action="{{ route('clone.openload.files') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input name="file" type="file" class="form-control input-lg" placeholder="Upload File">
                        <br>
                        {{--app('captcha')->display($attributes = ['data-badge' => 'bottomleft', 'data-callback' => 'onSubmit'])--}}
                        <button type="submit" class="btn btn-primary btn-lg">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- END Contact -->
</main>
@endsection

@section('scripts')
    <script>
        $(".g-recaptcha").addClass("btn btn-primary btn-lg").html("Send");

        function onSubmit(token) {
            $("#openload-form").submit();
            $(".g-recaptcha").prop("disabled", true);
        }
    </script>
@endsection
