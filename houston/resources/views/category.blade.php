@php
    use Jenssegers\Agent\Agent;
    $agent = new Agent();

    $x = 1;
    $i = 1;
    $rand = round((rand(1, count($media))) / 2);
@endphp
@extends('layouts.app', ['title' => $category->name])
@section('styles')
    <script>
        window.onbeforeunload = function() {
            $.ajax({
                // Query to server
                async: false,
                method: "POST",
                url: "{{ route('home.clear.session') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                }
            });
        }
    </script>

    <style>
        ins {
            border: none;
        }
    </style>
@endsection

@section('content')
 <!-- Main container -->
<main>
    <div class="container">
        <div class="text-center txt-gray"><h3><i class="fa {{ $category->icon }} }} "></i> {{ $category->name }}</h3></div>
    </div>

    @include('components.page_links', ['view' => 'category'])

    @include('components.media_items', ['view' => 'category'])

</main>
 <!-- END Main container -->

@endsection

@section('scripts')
    <script src="{{ url('assets/js/infinite-loading.js') }}"></script>
    <script src="{{ url('assets/js/ResizeSensor.js') }}"></script>
    <script>
        // Toggle the title length on resizing the media elements
        var titles = [];
        var lengths = [];
        var newTitle = "";

        $('.title-header').each(function() {
            var title = $(this).text();
            var length = $(this).text().length;

            titles.push(title);
            lengths.push(length);
        });

        function hideAndSliceElements() {
            if($('.changeSize').first().width() >= 330) {
                for (var x = 0, len = titles.length; x < len; x++) {
                    if (lengths[x] <= 33) {
                        newTitle = titles[x];

                        $("#nonad" + (x + 1)).find(".title-header").text(newTitle);
                    }
                }

                $('.stats').show();
            }
            else {
                for (var x = 0, len = titles.length; x < len; x++) {
                    if (lengths[x] > 27) {
                        newTitle = titles[x].slice(0, -9) + "...";
                        $("#nonad"+ (x + 1)).find(".title-header").text(newTitle);
                    }
                }

                $('.stats').hide();
            }
        }

        hideAndSliceElements();

        new ResizeSensor($('#col1'), function(){
            hideAndSliceElements();
        });

        // Function called if AdBlock is not detected
        function adBlockNotDetected() {
            ga('send', 'event', 'Blocking Ads', 'No', {'nonInteraction':1});
        }
        // Function called if AdBlock is detected
        function adBlockDetected() {
            setTimeout(function() {
                PNotify.prototype.options.styling = "bootstrap3";
                new PNotify({
                    title: 'Welcome to Clooud! <i class="fa fa-heart"></i>',
                    text: 'Hello there! In order to serve you even better, please support us by turning off your AdBlocker. The fair and ethically right usage of ads is what we strive for. <br><br>We don\'t like ads either. <i class="fa fa-thumbs-o-down"></i>',
                    width: '450px',
                    hide: false,
                    type: 'info'
                });
            }, 10000);

            ga('send', 'event', 'Blocking Ads', 'Yes', {'nonInteraction':1});
        }

        // If the file is not called, the variable does not exist 'blockAdBlock'
        // This means that AdBlock is present
        if (typeof blockAdBlock === 'undefined') {
            adBlockDetected();
        } else {
            blockAdBlock.onDetected(adBlockDetected);
            blockAdBlock.onNotDetected(adBlockNotDetected);
        }

        $(document).ready(function() {

            var loading_options = {
                finishedMsg: "<div class='text-center end-msg'>End Of Media!</div>",
                msgText: "",
                img: "/assets/images/loading.gif"
            };

            $('#content').infinitescroll({
                loading: loading_options,
                navSelector: "ul.pagination",
                nextSelector: "ul.pagination a:first",
                itemSelector: "#content .media_item"
            });

            $('.grid').click(function(){

                window.url = $(this).data('url');

                window.location = window.url;

                return false;
            });

            $('.media_item a').click(function(){

                window.location = $(this).attr('href');
            });

            $(document).on('click','.likes',function(){

                if(auth == false){
                    window.location = "{{ url('login') }}";
                }
                var likes = this;

                $.ajax({
                    url: "{{ route('media.like') }}",
                    method: "PUT",
                    type: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(likes).find('.like').data('id'),
                    },
                    success: function(data){
                        $(likes).find('.likes_counter').text(data);
                    }
                });
            });

            $(window).unbind('.infscr');

        });
    </script>
@endsection
