@inject('messages', 'App\Message')
<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('teams.index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>C</b>Me</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Honest</b>Sports</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-lg fa-envelope-o"></i>
                        <span class="label label-success"
                              style="font-size:12px;">{{ $messages->unreadMessages()->count() }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You Have ({{ $messages->unreadMessages()->count() }}) Unread Messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @foreach($messages->unreadMessages() as $message)
                                    <li><!-- start message -->
                                        <a href="{{ route('messages.show', $message->id) }}">
                                            <div class="pull-left">
                                                {!! Html::image(getAvatarUrl(0), null, ['class'=>'img-circle']) !!}
                                            </div>
                                            <h4>
                                                {{ str_limit($message->name, 15) }}
                                                <small>
                                                    <i class="fa fa-clock-o"></i> {{ $message->created_at->diffForHumans() }}
                                                </small>
                                            </h4>
                                            <p>{{ str_limit($message->title, 30) }}</p>
                                        </a>
                                    </li><!-- end message -->
                                @endforeach
                            </ul>
                        </li>
                        <li class="footer"><a href="{{ route('messages.index') }}">see all Messages</a></li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {!! Html::image(getAvatarUrl(Auth::user()->id), null, ['class'=>'user-image']) !!}
                        <span class="hidden-xs">Admin</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            {!! Html::image(getAvatarUrl(Auth::user()->id), null, ['class'=>'img-circle']) !!}
                            <p>
                                {{ Auth::user()->username }} <span
                                        class="label label-info">{{ Auth::user()->type }}</span>
                                <small>Member Since: {{ Auth::user()->created_at->format('Y-m-d') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ route('user.profile.index',Auth::user()->username) }}"
                                   class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-left">
                                <a href="{{ url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="btn btn-default btn-flat">Logout</a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
