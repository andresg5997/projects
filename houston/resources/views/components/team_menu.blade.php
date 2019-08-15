<div class="row team-menu">
    <div class="col-sm-12">
        <ul class="breadcrumb team-menu-list">
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
            <li>
                <a href="{{ route('lineup.index', $team->id) }}" {!! (Request::is('teams/*/lineup') || Request::is('teams/*/lineup/*') ? 'class="active"' : '') !!}><i class="fa fa-th-list"></i> {{ trans('header.lineup') }} </a>
            </li>
        </ul>
    </div>
</div>
