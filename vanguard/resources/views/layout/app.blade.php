<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>NetWork Latino</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Styles -->
  <link type="text/css" rel="stylesheet" href="{{asset('css/materialize.min.css')}}" media="screen,projection">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <style>
    .indicator{
      background-color: #2196F3!important;
    }
    .password-reset{
      margin-top: 1rem;
    }
  </style>
</head>
<body class="grey lighten-4">
  <div class="navbar-fixed">
    <nav>
      <div class="nav-wrapper white-responsive">
        <a href="{{ route('home') }}">
          <img class="responsive-img brand-logo full-height center" src="{{ asset('img/network-latino.jpg')}}">
        </a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul id="nav-mobile" class="nav-content left blue darken-4 full-height hide-on-med-and-down">
        </ul>
        <ul id="nav-mobile" class="left nav-grad grad-left full-height hide-on-med-and-down"></ul>
        <ul id="nav-mobile" class="nav-content right blue darken-4 full-height hide-on-med-and-down">
          <li class="right"><a class="waves-effect" href="{{ route('login') }}">Iniciar Sesión</a></li>
        </ul>
        <ul id="nav-mobile" class="right nav-grad grad-right full-height hide-on-med-and-down"></ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a class="waves-effect" href="{{ route('login') }}">Iniciar Sesión</a></li>
        </ul>
      </div>
    </nav>
  </div>
  <br>
  @yield('content')
  <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/materialize.min.js')}}"></script>
  <script>
    $(document).ready(function(){
    $(".button-collapse").sideNav();
    });
  </script>
</body>
</html>
