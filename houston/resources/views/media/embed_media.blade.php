<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $website_desc }}">
    <meta name="keywords" content="{{ $website_keywords }}">

    @include('components.media_styles', ['media' => $media])

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('assets/css/theshots.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/skins/skin-blue.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    @if ($media->type == 'audio' || $media->type == 'video')
        <style>
            *{outline:0!important;}
            #main{height:100%;width:100%;overflow:hidden;}
            body{height:100%;width:100%;margin:0;overflow:hidden;margin:0;background-color:transparent;}
            html{height:100%;}

            .media-container{position:relative;height:100%;}
            .media-container span.title{position:absolute;z-index:99;width:100%;top:0;background-color:#000;background-color:rgba(0,0,0,0.5);background:-moz-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:-webkit-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:-ms-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:linear-gradient(to bottom,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);color:white;height:20px;overflow:hidden;word-wrap:break-word;word-break:break-all;font-size:14px;display:block;text-align:center;font-family:Arial,sans-serif;}
            .media-container .video-js{width:100%;height:100%;}
            .video-js .vjs-poster{z-index:1;}
            .video-js:hover .vjs-big-play-button,.vjs-default-skin .vjs-big-play-button:focus{background-color:#0af;}
            .video-js .vjs-big-play-button {
                z-index:1;
                font-size: 4em;
                line-height: 1.5em;
                height: 1.5em;
                width: 3em;
                /* 0.06666em = 2px default */
                border: 0.06666em solid #fff;
                /* 0.3em = 9px default */
                border-radius: 0.3em;
                /* Align center */
                left: 50%;
                top: 50%;
                margin-left: -1.5em;
                margin-top: -0.75em;
                -moz-border-radius:9px;
                -webkit-border-radius:9px;
            }

            @media only screen and (max-width:340px) {.video-js .vjs-control-bar .vjs-playback-rate,.video-js .vjs-control-bar .vjs-captions-button{display:none;}}
            @media only screen and (max-width:440px) {.video-js .vjs-control-bar .vjs-ol-button{display:none;}}
            @media only screen and (max-width:500px) {.video-js .vjs-control-bar .vjs-remaining-time{display:none;}}
        </style>
    @else
        <style>
            *{outline:0!important;}
            #main{height:100%;width:100%;overflow:hidden;}
            body{height:100%;width:100%;margin:0;overflow:hidden;margin:0;background-color:transparent;}
            .image-gallery{height:50%;width:100%;margin:0;overflow:hidden;margin:0;background-color:transparent;}
            html{height:100%;}

            .media-container{position:relative;height:100%;}
            .media-container span.title{position:absolute;z-index:99;width:100%;top:0;background-color:#000;background-color:rgba(0,0,0,0.5);background:-moz-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:-webkit-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:-ms-linear-gradient(top,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);background:linear-gradient(to bottom,rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.45) 89%,rgba(0,0,0,0.2) 100%);color:white;height:20px;overflow:hidden;word-wrap:break-word;word-break:break-all;font-size:14px;display:block;text-align:center;font-family:Arial,sans-serif;}
        </style>
    @endif

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91752585-1', 'auto');
        ga('send', 'pageview');
    </script>

</head>

<body>
<div id="main">
    <div class="media-container">
        <span class="title"><p class="small">{{ $media->title }} - presented on Clooud.tv</p></span>
        <!-- Media -->
        @if ($media->type == 'video')
            <video
                id="media"
                class="video-js vjs-big-play-centered"
                poster="{{ $media->posterUrl() }}"
                data-setup="{}">

                <source src="{{ $media->downloadUrl() }}" type="video/{{ $media->streamExtension('video') }}">
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                    <a href="http://browsehappy.com/" target="_blank">supports HTML5 video</a>
                </p>
            </video>
        @elseif ($media->type == 'audio')
            <audio
                id="media"
                class="video-js vjs-default-skin col-md-12">

                <source src="{{ $media->downloadUrl() }}" type="audio/{{ $media->streamExtension('audio') }}">
                <p class="vjs-no-js">
                    To listen to this audio please enable JavaScript, and consider upgrading to a web browser that
                    <a href="http://browsehappy.com/" target="_blank">supports HTML5 audio</a>
                </p>
            </audio>
        @elseif ($media->type == 'picture')
            <ul class="image-gallery">
                <li data-thumb="{{ $media->previewImageUrl('thumbnail-l') }}" data-src="{{ $media->previewImageUrl('original') }}">
                    <img src="{{ $media->previewImageUrl('original') }}" alt="thumb">
                </li>
                @if(is_array($media->isGallery()))
                    @foreach($media->isGallery()['file_paths'] as $key => $file_path)
                        @if($file_path && url($file_path) != $media->previewImageUrl('original'))
                            <li data-thumb="{{ url($media->isGallery()['file_paths_thumb'][$key]) }}"
                                data-src="{{ url($file_path) }}">
                                <img src="{{ url($file_path) }}" alt="thumb">
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        @endif
    </div>
</div>

<!-- Scripts -->
@if (config('advertisements_active'))
    {!! config('embed_page_interstitial') !!}
    {!! config('embed_page_pop_under') !!}
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{ url('assets/js/pnotify.custom.min.js') }}"></script>
<script>var blockAdBlock = undefined;</script>
<script src="{{ url('assets/js/blockadblock.js') }}"></script>

@include('components.media_scripts', ['media' => $media, 'view' => 'embed'])

@if ($media->type == 'video' || $media->type == 'audio')
    @include('components.videojs_player', ['media' => $media, 'view' => 'embed'])
@endif

</body>
</html>
