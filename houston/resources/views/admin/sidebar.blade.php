<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                {!! Html::image(getAvatarUrl(Auth::user()->id), null, ['class'=>'img-circle']) !!}
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->username }}</p>
                <a><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ ($menu == 'dashboard') ? 'active' :'' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span style="padding-left:10px;">Dashboard</span>
                </a>
            </li>
            <li class="{{ ($menu == 'appearance') ? 'active' :'' }}" class="treeview">
                <a href="">
                    <i class="fa fa-desktop"></i>
                    <span style="padding-left:10px;">Appearance</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.appearance.menu') }}"><i class="fa fa-circle-o"></i> Menus</a></li>
                    <li><a href="{{ route('admin.appearance.themes') }}"><i class="fa fa-circle-o"></i> Themes</a></li>
                </ul>
            </li>
            <li class="{{ ($menu == 'archives') ? 'active' :'' }}" class="treeview">
                <a href="">
                    <i class="fa fa-folder"></i>
                    <span style="padding-left:10px;">Archives</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.archives.index') }}"><i class="fa fa-circle-o"></i> All Archives</a></li>
                </ul>
            </li>
            <li class="{{ ($menu == 'categories') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-folder-open"></i>
                    <span style="padding-left:10px;">Categories</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-folder"></i> All Categories</a></li>
                    <li><a href="{{ route('categories.create') }}"><i class="fa fa-plus-circle"></i> Add Category</a></li>
                </ul>
            </li>
            <li class="{{ ($menu == 'comments') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-comments-o"></i>
                    <span style="padding-left:10px;">Comments</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('comments.index') }}"><i class="fa fa-circle-o"></i> All Comments</a></li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'events') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-calendar-o"></i>
                    <span style="padding-left:10px;"> Events</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.events.index') }}"><i class="fa fa-circle-o"></i> All Events</a></li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'types') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-users"></i>
                    <span style="padding-left:10px;"> Event Types</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.types.index') }}"><i class="fa fa-circle-o"></i> All Event Types</a></li>
                    <li><a href="{{ route('admin.types.create') }}">
                        <i class="fa fa-plus-circle"></i> Add Event Type</a>
                    </li>
                </ul>
            </li>
            <li class="{{ ($menu == 'forum') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-envelope-o"></i>
                    <span style="padding-left:10px;">Forum</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('forum.index') }}"><i class="fa fa-folder"></i> All Categories</a></li>
                    <li><a href="{{ route('forum.create') }}"><i class="fa fa-plus-circle"></i> Add Category</a></li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'media') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-paper-plane"></i>
                    <span style="padding-left:10px;"> Media</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('medias.index') }}"><i class="fa fa-circle-o"></i> All Media</a></li>
                </ul>
            </li>
            <li class="{{ ($menu == 'pages') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-files-o"></i>
                    <span style="padding-left:10px;">Pages</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('pages.index') }}"><i class="fa fa-circle-o"></i> All Header Pages</a></li>
                    <li><a href="{{ route('pages.create') }}"><i class="fa fa-plus-circle"></i> Add Header Page</a></li>
                    <li>&nbsp;</li>
                    <li><a href="{{ route('footer-pages.index') }}"><i class="fa fa-circle-o"></i> All Footer Pages</a></li>
                    <li><a href="{{ route('footer-pages.create') }}"><i class="fa fa-plus-circle"></i> Add Footer Page</a></li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'sports') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-soccer-ball-o"></i>
                    <span style="padding-left:10px;"> Sports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.sports.index') }}"><i class="fa fa-circle-o"></i> All Sports</a></li>
                    <li><a href="{{ route('admin.sports.create') }}">
                        <i class="fa fa-plus-circle"></i> Add Sport</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'teams') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-flag"></i>
                    <span style="padding-left:10px;"> Teams</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.teams.index') }}"><i class="fa fa-circle-o"></i> All Teams</a></li>
                </ul>
            </li>
            <li class="treeview {{ ($menu == 'users') ? 'active' :'' }}">
                <a href="">
                    <i class="fa fa-users"></i>
                    <span style="padding-left:10px;"> Users</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('users.index') }}"><i class="fa fa-circle-o"></i> All Users</a></li>
                </ul>
            </li>
            <li class="header">ADVANCED</li>
            <li class="{{ ($menu == 'settings') ? 'active' : '' }}">
                <a href="">
                    <i class="fa fa-cog"></i>
                    <span style="padding-left:10px;">Settings</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('settings.adblock') ? 'active' : '' }}"><a href="{{ route('settings.adblock') }}"><i class="fa fa-circle-o"></i> Adblock </a></li>
                    <li class="{{ Route::is('settings.advertisements') ? 'active' : '' }}"><a href="{{ route('settings.advertisements') }}"><i class="fa fa-circle-o"></i> Advertisements </a></li>
                    <li class="{{ Route::is('settings.affiliate') ? 'active' : '' }}"><a href="{{ route('settings.affiliate') }}"><i class="fa fa-circle-o"></i> Affiliate </a></li>
                    <li class="{{ Route::is('settings.analytics') ? 'active' : '' }}"><a href="{{ route('settings.analytics') }}"><i class="fa fa-circle-o"></i> Analytics </a></li>
                    <li class="{{ Route::is('settings.cache') ? 'active' : '' }}"><a href="{{ route('settings.cache') }}"><i class="fa fa-circle-o"></i> Cache </a></li>
                    <li class="{{ Route::is('settings.captcha') ? 'active' : '' }}"><a href="{{ route('settings.captcha') }}"><i class="fa fa-circle-o"></i> Captcha</a></li>
                    <li class="{{ Route::is('settings.comments') ? 'active' : '' }}"><a href="{{ route('settings.comments') }}"><i class="fa fa-circle-o"></i> Comments </a></li>
                    <li class="{{ Route::is('settings.email') ? 'active' : '' }}"><a href="{{ route('settings.email') }}"><i class="fa fa-circle-o"></i> Email </a></li>
                    <li class="{{ Route::is('settings.media') ? 'active' : '' }}"><a href="{{ route('settings.media') }}"><i class="fa fa-circle-o"></i> Media </a></li>
                    <li class="{{ Route::is('settings.points') ? 'active' : '' }}"><a href="{{ route('settings.points') }}"><i class="fa fa-circle-o"></i> Points </a></li>
                    <li class="{{ Route::is('settings.social.links') ? 'active' : '' }}"><a href="{{ route('settings.social.links') }}"><i class="fa fa-circle-o"></i> Social Links </a></li>
                    <li class="{{ Route::is('settings.social.login') ? 'active' : '' }}"><a href="{{ route('settings.social.login') }}"><i class="fa fa-circle-o"></i> Social Login </a></li>
                    <li class="{{ Route::is('settings.storage') ? 'active' : '' }}"><a href="{{ route('settings.storage') }}"><i class="fa fa-circle-o"></i> Storage </a></li>
                    <li class="{{ Route::is('settings.tags') ? 'active' : '' }}"><a href="{{ route('settings.tags') }}"><i class="fa fa-circle-o"></i> Tags </a></li>
                    <li class="{{ Route::is('settings.general') ? 'active' : '' }}"><a href="{{ route('settings.general') }}"><i class="fa fa-circle-o"></i> Website </a></li>
                </ul>
            </li>

            <li class="{{ ($menu == 'advanced') ? 'active' : '' }}">
                <a href="">
                    <i class="fa fa-cogs"></i>
                    <span style="padding-left:10px;">Advanced</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Route::is('advanced.backups') ? 'active' : '' }}"><a href="{{ route('advanced.backups') }}"><i class="fa fa-files-o"></i> Backups </a></li>
                    <li class="{{ Route::is('advanced.logs') ? 'active' : '' }}"><a href="{{ route('advanced.logs') }}"><i class="fa fa-terminal"></i> Logs </a></li>
                    <li class="{{ Route::is('advanced.recommended') ? 'active' : '' }}"><a href="{{ route('advanced.recommended') }}"><i class="fa fa-info-circle"></i> Recommended Settings </a></li>
                </ul>
            </li>
            <li class="header">LABELS</li>
            <li class="{{ ($menu == 'messages') ? 'active' :'' }}">
                <a href="{{ route('messages.index') }}"><i class="fa fa-inbox text-yellow"></i>
                    <span>Messages</span></a>
            </li>

            <li class="{{ ($menu == 'flags') ? 'active' :'' }}">
                <a href="{{ route('flags.index') }}"><i class="fa fa-flag text-red"></i> <span>Flags</span></a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
