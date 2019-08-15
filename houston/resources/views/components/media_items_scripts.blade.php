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

    @if (config('adblock_active'))
        // Function called if AdBlock is not detected
        function adBlockNotDetected() {
            @if (config('analytics_active'))
                ga('send', 'event', 'Blocking Ads', 'No', {'nonInteraction':1});
            @endif
        }
        // Function called if AdBlock is detected
        function adBlockDetected() {
            setTimeout(function() {
                PNotify.prototype.options.styling = "bootstrap3";
                new PNotify({
                    title: 'Welcome to {{ explode('.', ucfirst(request()->getHost()))[0] }}! <i class="fa fa-heart"></i>',
                    text: '{!! config('adblock_notification_message') !!}',
                    width: '450px',
                    hide: false,
                    type: 'info'
                });
            }, {{ config('adblock_popup_time') * 1000 }});

            @if (config('analytics_active'))
                ga('send', 'event', 'Blocking Ads', 'Yes', {'nonInteraction':1});
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
    @endif

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