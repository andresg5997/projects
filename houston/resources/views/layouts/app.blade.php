@php
    $agent = new Jenssegers\Agent\Agent();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $website_desc }}">
    <meta name="keywords" content="{{ $website_keywords }}">

    @if( Request::is( Config::get('chatter.routes.home')) )
        <title>{{ config('website_title', null) }} | Forums</title>
        <meta property="og:title" content="{{ config('website_title', null) }} | Forums"/>
    @elseif( Request::is( Config::get('chatter.routes.home') . '/' . Config::get('chatter.routes.category') . '/*' ) && isset( $discussion ) )
        <title>{{ config('website_title', null) }} | {{ $discussion->category->name }}</title>
        <meta property="og:title" content="{{ config('website_title', null) }} | {{ $discussion->category->name }}"/>
    @elseif( Request::is( Config::get('chatter.routes.home') . '/*' ) && isset($discussion->title))
        <title>{{ config('website_title', null) }} | {{ $discussion->category->name }} - {{ $discussion->title }}</title>
        <meta property="og:title" content="{{ config('website_title', null) }} | {{ $discussion->category->name }} - {{ $discussion->title }}"/>
    @else
        <title>{{ config('website_title', null) . (isset($title) ? ' | ' . $title : "") }}</title>
    @endif

    <!-- OG for Social Media -->
    @if (! Request::is('media/*') && ! Request::is('m/*'))
        <meta property="og:title" content="{{ config('website_title', null) . (isset($title) ? ' | ' . $title : "") }}"/>
        <meta property="og:image" content="{{ url('assets/images/social.png') }}"/>
        <meta property="og:site_name" content="{{ config('website_title', null) }} Media"/>
        <meta property="og:description" content="{{ $website_desc }}"/>

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('website_title', null) . (isset($title) ? ' | ' . $title : "") }}">
        <meta name="twitter:description" content="{{ $website_desc }}">
        <meta name="twitter:image" content="{{ url('assets/images/social.png') }}">
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/assets/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ url('assets/css/theshots.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/skins/skin-'.config("theme").'.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/themify-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/toastr/css/toastr.css') }}">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}"> --}}
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/fonts.css') }}">
    <link href="{{url('assets/css/pnotify.custom.min.css')}}" rel="stylesheet">

    @yield('styles')

    @if(Request::is( Config::get('chatter.routes.home') ) || Request::is( Config::get('chatter.routes.home') . '/*' ))
        <!-- Chatter Modification -->
        <link href="{{url('assets/css/chatter-mod.css')}}" rel="stylesheet">
    @endif

    <!-- Fonts -->
    {{-- <link href='//fonts.googleapis.com/css?family=Raleway:100,300,400,500,600,800%7COpen+Sans:300,400,500,600,700,800%7CMontserrat:400,700' rel='stylesheet' type='text/css'> --}}

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="144x144" href="{{url('assets/images/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" href="{{url('assets/images/favicons/favicon.png')}}" sizes="32x32">
    <link rel="manifest" href="{{url('assets/images/favicons/manifest.json')}}">
    <link rel="mask-icon" href="{{url('assets/images/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">

    @if (config('analytics_active'))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', '{{ ! empty (config('google_analytics_id')) ? config('google_analytics_id') : '' }}', 'auto');
            ga('send', 'pageview');
        </script>
    @endif

</head>

<body class="sticky-nav">
@if(!isset($noHeader))
    <nav class="container-fluid navbar" id="blue-nav">
        <div class="row">
            <div class="col-md-1 d-flex align-items-center justify-content-center" style="margin-top: 25px">
                @if(isset($team))
                    <div class="dropdown bars-box hidden-lg">
                        <a class="dropdown-toggle" id="teamDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="javascript:;">
                            <i class="glyphicon glyphicon-menu-hamburger fa-lg"></i>
                        </a>
                          <ul class="dropdown-menu dropdown-menu-left dropdown-team" aria-labelledby="teamDropdown">
                            <li class="menu-close">
                                <div class="bars-box">
                                    <span id="close-icon" class="glyphicon glyphicon-remove" style="font-size: 24px"></span>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('teams.show', $team->id) }}" {!! (Request::is('teams') || Request::is('teams/' . $team->id) ? 'class="active"' : '') !!}><i class="fa fa-flag"></i> {{ trans('header.team') }} </a>
                            </li>
                            <li>
                                <a href="{{ route('roster.index', $team->id) }}" {!! (Request::is('roster') || Request::is('teams/*/roster') ? 'class="active"' : '') !!}><i class="fa fa-users"></i> {{ trans('header.roster') }} </a>
                            </li>
                            <li>
                                <a href="{{ route('schedule.index', $team->id) }}" {!! (Request::is('schedule') || Request::is('teams/*/schedule') ? 'class="active"' : '') !!}><i class="fa fa-calendar-alt"></i> {{ trans('header.schedule') }} </a>
                            </li>
                            <li>
                                <a href="{{ route('availability.index', $team->id) }}" {!! (Request::is('teams/*/availability') || Request::is('teams/*/availability/*') ? 'class="active"' : '') !!}><i class="fa fa-calendar-check"></i> {{ trans('header.availability') }} </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('medias.index') }}" {!! (Request::is('media') || Request::is('media/*') ? 'class="active"' : '') !!}><i class="fa fa-folder"></i> {{ trans('header.media') }} </a>
                            </li> --}}
                            <li>
                                <a href="{{ route('assignments.index', $team->id) }}" {!! (Request::is('teams/*/assignments') || Request::is('teams/*/assignments/*') ? 'class="active"' : '') !!}><i class="fa fa-check-square"></i> {{ trans('header.assignments') }} </a>
                            </li>
                            @if($team->isOwner(\Auth::id()))
                                <li>
                                    <a href="{{ route('lineup.index', $team->id) }}" {!! (Request::is('teams/*/lineup') || Request::is('teams/*/lineup/*') ? 'class="active"' : '') !!}><i class="fa fa-th-list"></i> {{ trans('header.lineup') }} </a>
                                </li>
                            @endif
                            <li style="width:100%">
                                <a href="{{ route('user.profile.index', Auth::user()->username) }}"><i class="fa fa-user"></i> {{ trans('header.profile') }}</a>
                            </li>
                            @if(\Auth::user()->type == 'admin')
                                <li class="ml-0" style="width:100%">
                                    <a style="color: #f78080 !important" href="{{ route('admin.dashboard') }}"><i class="fa fa-lock"></i> {{ trans('header.admin_panel') }} </a>
                                </li>
                            @endif
                            <li class="ml-0" style="width:100%">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-log-out"></i> {{ trans('header.logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </li>
                          </ul>
                    </div>
                @endif
            </div>
            <div class="col-md-10 hidden-md">
                <ul class="blue-nav">
                    <li class="pull-left" style="padding: 0">
                        <a href="{{ route('teams.index') }}">
                            <img src="{{ asset('img/logo.png') }}" height="100px" style="position: relative; top: 0; left: 0" alt="">
                        </a>
                    </li>
                        @if(\Auth::check())
                            <div class="pull-right" style="height: 100px; display: flex; align-items: center">
                                <li>
                                    <a href="{{ route('teams.index') }}">
                                        <i class="fa fa-home"></i>
                                        {{ trans('header.your_teams') }}
                                    </a>
                                </li>
                                @if(isset($team))

                                <li>
                                    <a href="{{ route('teams.show', $team->id) }}" {!! (Request::is('teams') || Request::is('teams/' . $team->id) ? 'class="active"' : '') !!}><i class="fa fa-flag"></i> {{ trans('header.home') }} </a>
                                </li>
                                <li>
                                    <a href="{{ route('roster.index', $team->id) }}" {!! (Request::is('roster') || Request::is('teams/*/roster') ? 'class="active"' : '') !!}><i class="fa fa-users"></i> {{ trans('header.roster') }} </a>
                                </li>
                                <li>
                                    <a href="{{ route('schedule.index', $team->id) }}" {!! (Request::is('schedule') || Request::is('teams/*/schedule') ? 'class="active"' : '') !!}><i class="fa fa-calendar-alt"></i> {{ trans('header.schedule') }} </a>
                                </li>
                                <li>
                                    <a href="{{ route('availability.index', $team->id) }}" {!! (Request::is('teams/*/availability') || Request::is('teams/*/availability/*') ? 'class="active"' : '') !!}><i class="fa fa-calendar-check"></i> {{ trans('header.availability') }} </a>
                                </li>
                                <li>
                                    <a href="{{ route('assignments.index', $team->id) }}" {!! (Request::is('teams/*/assignments') || Request::is('teams/*/assignments/*') ? 'class="active"' : '') !!}><i class="fa fa-check-square"></i> {{ trans('header.assignments') }} </a>
                                </li>
                                @if($team->isOwner(\Auth::id()))
                                    <li>
                                        <a href="{{ route('lineup.index', $team->id) }}" {!! (Request::is('teams/*/lineup') || Request::is('teams/*/lineup/*') ? 'class="active"' : '') !!}><i class="fa fa-th-list"></i> {{ trans('header.lineup') }} </a>
                                    </li>
                                @endif
                            @endif
                            <li>
                                <a href="#!" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ getAvatarUrl(Auth::user()->id) }}" height="30px">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                  </a>
                                  <ul class="dropdown-menu">
                                    <li class="ml-0" style="width:100%">
                                        <a href="{{ route('user.profile.index', Auth::user()->username) }}"><i class="glyphicon glyphicon-user"></i> {{ trans('header.profile') }}</a>
                                    </li>
                                    @if(\Auth::user()->type == 'admin')
                                        <li class="ml-0" style="width:100%">
                                            <a style="color: #f78080 !important" href="{{ route('admin.dashboard') }}"><i class="glyphicon glyphicon-lock"></i> {{ trans('header.admin_panel') }} </a>
                                        </li>
                                    @endif
                                    <li class="ml-0" style="width:100%">
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-log-out"></i> {{ trans('header.logout') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                                    </li>
                                  </ul>
                            </li>
                        </div>
                        @endif
                </ul>
            </div>
        </div>
    </nav>
    <div style="padding-top: 92px"></div>
@endif
@yield('content')

<!-- Site footer -->
<div class="grad"></div>
<footer class="site-footer" style="background: white; color:white">

    <!-- Bottom section -->
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 d-flex" style="justify-content: space-between">
                <img src="{{ asset('img/logo-full.png') }}" height="180px">
                <div class="footer-divider"></div>
            </div>
            @if(App\Page::orderFooter(0)->count())
                @foreach(App\Page::orderFooter(0) as $parent)
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        @if($parent->name != 'Social Media')
                            <h5>{{ $parent->name }}</h5>
                            <ul class="footer-links">
                                @foreach(App\Page::orderFooter($parent->id) as $page)
                                    <li><a class="footer-font" href="{{ route('page.footer.show', [$parent->slug, $page->slug]) }}">{{ $page->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                        @if($loop->last)
                                <ul class="social-icons">
                                    @php
                                        $social_links = Share::load(url('/'), config("website_title"))->services('gplus', 'facebook', 'twitter', 'reddit', 'email');
                                    @endphp
                                    <li><a class="facebook" href="{{ ! empty(config('facebook')) ? config('facebook') : $social_links['facebook'] }}"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a class="twitter" href="{{ ! empty(config('twitter')) ? config('twitter') : $social_links['twitter'] }}"><i class="fab fa-twitter"></i></a></li>
                                    @if (! empty(config('instagram')))
                                        <li><a class="instagram" href="{{ config('instagram') }}"><i class="fab fa-instagram"></i></a></li>
                                    @endif
                                    <li><a class="google-plus" href="{{ $social_links['gplus'] }}"><i class="fab fa-google-plus-g"></i></a></li>
                                    <li><a class="reddit" href="{{ $social_links['reddit'] }}"><i class="fab fa-reddit"></i></a></li>
                                    <li><a class="send" href="{{ $social_links['email'] }}"><i class="fa fa-send"></i></a></li>
                                </ul>
                            @endif
                    </div>
                @endforeach
            @endif
        </div>
    <div class="row">
        <div class="col-sm-12" align="center">
            <span class="disclaimer">&copy; {{ date('Y') }} {{ config('website_title', null) }}</span>
        </div>
    </div>
    </div>
    <!-- END Bottom section -->
</footer>
<!-- END Site footer -->

<!-- Back to top button -->
<a id="scroll-up" href="#"><i class="fa fa-angle-up"></i></a>
<!-- END Back to top button -->

<!-- Scripts -->
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendors/toastr/js/toastr.min.js') }}"></script>
{{-- <script src="{{ url('assets/js/theshots.js') }}"></script> --}}
<script src="{{ url('assets/js/infinite-loading.js') }}"></script>
<script src="{{ url('assets/js/lightslider.min.js') }}"></script>
<script src="{{ url('assets/js/smoothscroll.min.js') }}"></script>
<script src="{{ url('assets/js/matchHeight.min.js') }}"></script>
<script src="{{ url('assets/js/custom.js') }}"></script>
<script src="{{ url('assets/js/pnotify.custom.min.js') }}"></script>
<script>var blockAdBlock = undefined;</script>
<script src="{{ url('assets/js/blockadblock.js') }}"></script>
<script>
    var auth = "{{ Auth::check() }}";

    $('.follow').on('click',function() {

        $(this).toggleClass('btn-success');

            if($(this).text() == 'UnFollow') {
                $(this).text('Follow')
            } else if($(this).text() == 'Follow'){
                $(this).text('UnFollow')
            }

        $.ajax({
            url: "{{ route('follow') }}",
            method: "PUT",
            type: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                id: $(this).data('id'),
            },
            success:function (data){
                //$('#followers_count').text(data)
                //$(likes).find('.likes_counter').text(data);
            }
        });
    });

</script>

@yield('scripts')

</body>
</html>
