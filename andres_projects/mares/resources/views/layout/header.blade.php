<!DOCTYPE html>
<html>
<head>
  <title>@yield('title') | MARES</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="{{ asset('css/materialize.min.css') }}"  media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/toastr/toastr.min.css') }}">
  @yield('styles')
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
	<ul id="dropdown1" class="dropdown-content">
        <li><a class="blue-text" href="{{ route('usuarios.show', Auth::user()->id) }}" class="waves-effect">Mi perfil</a></li>
        @admin
            <li><a class="blue-text" href="{{ route('usuarios.index') }}">Usuarios</a></li>
            <li><a class="blue-text" href="{{ route('estados.index') }}">Estados</a></li>
        @endadmin
	  <li class="divider"></li>
	  <li>
		  	<a class="waves-effect blue-text" href="{{ route('logout') }}"
		        onclick="event.preventDefault();
		                 document.getElementById('logout-form').submit();">
		        Cerrar sesión
		    </a>
		    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		        {{ csrf_field() }}
		    </form>
	  	</li>
	</ul>
	<div class="navbar-fixed">
	    <nav>
	        <div class="nav-wrapper white-responsive">
	        	<a href="{{ route('home') }}">
					<img class="responsive-img brand-logo full-height center" src="{{ asset('img/logo_mares.png')}}">
	            </a>
	            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul id="nav-mobile" class="nav-content left blue darken-4 full-height hide-on-med-and-down">
	                <li @if(Request::is('/')) class="active"@endif><a href="{{ route('home') }}" class="waves-effect"><i class="material-icons left">home</i> Inicio</a></li>
	                <li @if(Request::is('marcas') || Request::is('marcas/*')) class="active"@endif><a href="{{route('marcas.index')}}" class="waves-effect"><i class="material-icons left">business_center</i>Marcas</a></li>
	                <li @if(Request::is('oposiciones') || Request::is('oposiciones/*')) class="active"@endif><a href="{{ route('oposiciones.index') }}" class="waves-effect"><i class="material-icons left">search</i>Oposiciones</a></li>
	            </ul>
				<ul id="nav-mobile" class="left nav-grad grad-left full-height hide-on-med-and-down"></ul>
	            <ul id="nav-mobile" class="nav-content right blue darken-4 full-height hide-on-med-and-down">
	                <li style="float: right;"><a class="dropdown-button waves-effect" href="#!" data-beloworigin="true" data-activates="dropdown1"><i class="large material-icons left">account_circle</i> {{ Auth::user()->fullName() }}<i class="material-icons right">arrow_drop_down</i></a></li>
            		@include('layout.components.notifications')
	            </ul>
				<ul id="nav-mobile" class="right nav-grad grad-right full-height hide-on-med-and-down"></ul>
	            <ul class="side-nav" id="mobile-demo">
	                <li><a href="sass.html">Marcas</a></li>
	                <li><a href="badges.html">Oposiciones</a></li>
	                <li><a href="#"><i class="material-icons left">notifications</i><span class="new badge">3</span></a></li>
	                <!-- <li><a class="dropdown-button" href="#!" data-beloworigin="true" data-activates="dropdown1">Nombre de Usuario<i class="material-icons right">arrow_drop_down</i></a></li> -->
	            </ul>
	        </div>
	    </nav>
	</div>
