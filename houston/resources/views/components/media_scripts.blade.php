<script>
    @if($adblock_off == 1)
        function block_screen() {
            $('<div id="screenBlock"></div>').appendTo('body');
            $('#screenBlock').css( { opacity: 0, width: $(document).width(), height: $(document).height() } )
                .addClass('blockDiv')
                .animate({opacity: 0.9}, 200);

            PNotify.prototype.options.styling = "bootstrap3";
            const stack_center = {
                "dir1": "down",
                "dir2": "right",
                "firstpos1": 150,
                "firstpos2": ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2)
            };

            $(window).resize(function(){
                stack_center.firstpos2 = ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2);
            });

            new PNotify({
                title: '<center>Welcome to Clooud! <i class=\"fa fa-heart\"></i></center>',
                text: '<br>This publisher of this media chose to not share their content with you, unless your adblocking software whitelisted Clooud.tv.<br><br>The fair and ethically right usage of ads is what we strive for. <br><br>We don\'t like ads either. <i class="fa fa-thumbs-o-down"></i><br><br><center><a class=\"btn btn-round btn-white\" href=\"{{ $media->key }}\">Reload page</a></center>',
                width: '450px',
                hide: false,
                buttons: {
                    closer: false,
                    sticker: false
                },
                mobile: {
                    swipe_dismiss: false
                },
                type: 'info',
                stack: stack_center
            });
        }
    @endif

    // Function called if AdBlock is not detected
    let detected = 0;
    function adBlockNotDetected() {
        detected = '0';
        @if ($media->type == 'picture')
            sendRequest(detected);
        @endif
        ga('send', 'event', 'Blocking Ads', 'No', {'nonInteraction':1});
    }

    // Function called if AdBlock is detected
    function adBlockDetected() {
        @if($adblock_off == 1)
            block_screen();
        @endif
            detected = '1';
        @if ($media->type == 'picture')
            sendRequest(detected);
        @endif
        ga('send', 'event', 'Blocking Ads', 'Yes', {'nonInteraction':1});

        @if($adblock_ask == 1 && $adblock_off == 0)
            setTimeout(function() {
                PNotify.prototype.options.styling = "bootstrap3";
                new PNotify({
                    title: 'Welcome to Clooud! <i class=\"fa fa-heart\"></i>',
                    text: 'In order to serve you even better, please support us by turning off your AdBlocker. The fair and ethically right usage of ads is what we strive for. <br><br>We don\'t like ads either. <i class=\"fa fa-thumbs-o-down\"></i>',
                    width: '450px',
                    hide: false,
                    type: 'info'
                });
            }, 2500);
        @endif
    }

    // If the file is not called, the variable does not exist 'blockAdBlock'
    // This means that AdBlock is present
    if (typeof blockAdBlock === 'undefined') {
        adBlockDetected();
    } else {
        blockAdBlock.onDetected(adBlockDetected);
        blockAdBlock.onNotDetected(adBlockNotDetected);
    }

    @if (($media->type == 'video' || $media->type == 'audio' || $media->type == 'picture') and config('affiliate_active'))
        @php
            $embed = 0;
            if ($view == 'embed') {
                $embed = 1;
            }
        @endphp
        const url_unique = '{{ route("affiliate.create.$media->type", [$media->slug, $embed]) }}';
    @endif

    @if (($media->type == 'video' || $media->type == 'audio') and config('affiliate_active'))
        const media = document.getElementById("media");

        let duration = 0; // will hold length of the video in seconds
        let played = new Array(0);
        let percent_played = 0;
        let i = 10;

        function timeupdate() {
            const currentTime = parseInt(media.currentTime);
            // set the current second to "1" to flag it as played
            played[currentTime] = 1;
            // console.log("played is "+played);
            // sum the value of the array (add up the "played" seconds)
            const sum = played.reduce(function(acc, val) {return acc + val;}, 0);
            percent_played = sum / duration * 100;
            // timeupdate seems to get called on load of media before playing
            // hence division by 0 will result in Infinity
            if (! $.isNumeric(percent_played)) {
                percent_played = 0;
            }
            // console.log("percent_played "+percent_played);

            if (percent_played > 100) {
                percent_played = 100;
            }

            if (i <= percent_played) {
                // console.log(floor10(percent_played, 1) + '% played');
                i += 10;
                // console.log(detected);
                sendRequest(detected);
            }
        }

        function getDuration() {
            // get the duration in seconds, rounding down, to size the array
            duration = parseInt(roundDown(media.duration, 1));
            // resize the array, defaulting entries to zero
            // played.resize(duration,0);
            resize(played, duration, 0);
        }

        function resize(arr, newSize, defaultValue) {
            while(newSize > arr.length)
                arr.push(defaultValue);
            arr.length = newSize;
        }

        function roundDown(num, precision) {
            return Math.floor(num * precision) / precision
        }

        function decimalAdjust(type, value, exp) {
            // If the exp is undefined or zero...
            if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
            }
            value = +value;
            exp = +exp;
            // If the value is not a number or the exp is not an integer...
            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
            }
            // Shift
            value = value.toString().split('e');
            value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
            // Shift back
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }

        function floor10(value, exp) {
            return decimalAdjust('floor', value, exp);
        }

        media.addEventListener('loadedmetadata', getDuration, false);
        media.addEventListener('timeupdate', timeupdate, false);
    @endif
    // Ajax Request
    @if (config('affiliate_active'))
        function sendRequest(detected) {
            $(document).ready(function(){

                @if ($media->type == 'video' || $media->type == 'audio')
                    const data = {
                        _token: "{{ csrf_token() }}",
                        duration: duration,
                        test: detected,
                        percent_played: floor10(percent_played, 1)
                    };
                    const timeOut = 0;
                @elseif ($media->type == 'picture')
                    const data = {
                        _token: "{{ csrf_token() }}",
                        test: detected
                    };
                    const timeOut = {{ config('image_duration_for_commission') * 1000 }};
                @endif

                setTimeout(function () {
                    $(function () {
                        $.ajax({
                            url: url_unique,
                            type: "POST",
                            data: data,
                            success: function () {
                                // console.log('success')
                            },
                            error: function () {
                                // console.log('error')
                            }
                        });
                    });
                }, timeOut);
            });
        }
    @endif
</script>

@if ($media->type == 'picture')
    <script>
        $(document).ready(function(){
            $('.image-gallery').lightSlider({
                gallery:true,
                item:1,
                loop:true,
                thumbItem: 5,
                slideMargin:0,
                enableDrag: true,
                currentPagerPosition:'right',
                onSliderLoad: function(el) {
                    el.lightGallery({
                        selector: '.image-gallery .lslide'
                    });
                }
            });
        });
    </script>

    <script src="{{ url('assets/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ url('assets/js/lightslider.min.js') }}"></script>
@endif

<script src="{{ url('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
