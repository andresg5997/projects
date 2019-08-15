@if ($media->type == 'video' || $media->type == 'audio')
    <link rel="stylesheet" href="//vjs.zencdn.net/5.19.1/video-js.min.css">
@endif

<link href="{{url('assets/css/pnotify.custom.min.css')}}" rel="stylesheet">

<meta property="og:title" content="{{ $media->title }}"/>
<meta property="og:site_name" content="{{ config('website_title', null) }} Media"/>
<meta property="og:description" content="{{ strip_tags($media->body) }}"/>

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $media->title }}">
<meta name="twitter:description" content="{{ strip_tags($media->body) }}">

@if ($media->type == 'video')
    <meta property="og:image" content="{{ $media->previewImageUrl() }}"/>
    <meta name="twitter:image" content="{{ $media->previewImageUrl() }}">

    <link rel="stylesheet" href="{{ url('assets/css/video-js-mod.css') }}">
@elseif ($media->type == 'audio')
    <meta property="og:image" content="{{ url('assets/images/sound.png') }}"/>
    <meta name="twitter:image" content="{{ url('assets/images/sound.png') }}">

    <style>
        .video-js .vjs-control.vjs-fullscreen-control {
            position: absolute;
            right: 0;
        }
        .vjs-brand-container {
            position: absolute;
            right: 40px;
        }
        /*
        make sure the custom controls are always visible because
        the plugin hides and replace the video.js native mobile
        controls
        */
        .vjs-using-native-controls .vjs-control-bar {
            display: flex !important;
        }
        #media {
            background-color: #ffffff;
        }
        .no-margins {
            margin-right: 0px;
            margin-left: 0px;
        }
    </style>
@elseif ($media->type == 'picture')
    <meta property="og:image" content="{{ $media->previewImageUrl('original') }}"/>
    <meta name="twitter:image" content="{{ $media->previewImageUrl('original') }}">

    <link rel="stylesheet" href="{{ url('assets/css/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/lightslider.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/lg-fb-comment-box.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/lg-transitions.min.css') }}">
@else
    <meta property="og:image" content="{{ url('assets/images/filetype-na.png') }}"/>
    <meta name="twitter:image" content="{{ url('assets/images/filetype-na.png') }}">
@endif

<link rel="stylesheet" href="{{ url('assets/admin/assets/plugins/sweet-alert/sweetalert.css') }}">
<style type="text/css">
    .blockDiv {
        position: absolute;
        top: 0px;
        left: 0px;
        background-color: #FFF;
        width: 0px;
        height: 0px;
        z-index: 10;
    }
</style>