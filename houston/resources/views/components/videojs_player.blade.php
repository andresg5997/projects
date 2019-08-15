<script src="//vjs.zencdn.net/5.19.1/video.min.js"></script>
<script src="{{ url('assets/js/videojs/videojs.hotkeys.min.js') }}"></script>
<script src="{{ url('assets/js/videojs/videojs-brand.js') }}"></script>
@if (config('analytics_active'))
    <script src="{{ url('assets/js/videojs/videojs.ga.js') }}"></script>
@endif

@if ($media->type == 'audio')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/1.3.7/wavesurfer.min.js"></script>
    <script src="{{ url('assets/js/videojs/videojs.wavesurfer.js') }}"></script>
@endif

<script>
    $(document).ready(function() {
        var player = videojs('media', {
            @if ($view != 'embed' && $media->type != 'audio')
                fluid: true,
            @endif
            controls: true,
            loop: false,
            height: 400,
            playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 1.75, 2]
        });

        player.brand({
            image: "{{ url('assets/images/brand.png') }}",
            title: "{{ config('website_title') }}",
            destination: "{{ url('/') }}",
            destinationTarget: "_top"
        });

        player.hotkeys({
            seekStep: 10,
            enableNumbers: false,
            volumeStep: .1,
            enableVolumeScroll: false
        });

        @if (config('analytics_active'))
            player.ga({
                'eventsToTrack': ['fullscreen', 'resize', 'play', 'percentsPlayed'],
                'debug': true
            });
        @endif

        @if ($media->type == 'audio')
            player.wavesurfer({
                src: '{{ $media->downloadUrl() }}',
                msDisplayMax: 10,
                // debug: true,
                waveColor: 'grey',
                progressColor: 'black',
                cursorColor: 'black',
                hideScrollbar: true
            });
        @endif

        player.on('play', addPlay);

        function addPlay() {
            $.ajax({
                url: "{{ route('media.add.play', $media->key) }}",
                method: "POST",
                type: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    //
                }
            });
        }
    });
</script>