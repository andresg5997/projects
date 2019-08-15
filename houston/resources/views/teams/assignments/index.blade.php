@extends('layouts.app', ['title' => trans('player.title_index')])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
@endsection

@section('content')
<div class="grass-bg">
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center" style="margin-top: 40px">
            <ol class="breadcrumb">
              <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
              <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
              <li class="active">{{ trans('breadcrumb.assignments') }}</li>
            </ol>
        </div>
    </div>

<div id="app">
    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6>{{ trans('assignment.assignments') }}</h6>
                    </div>
                    <div class="card-block table-overflow-x">
                        <h3 v-if="events.length === 0">{{ trans('availability.no_upcoming_events') }}</h3>
                        <table class="table table-bordered table-striped" v-else>
                            <thead>
                                <tr>
                                    <th><i class="fa fa-user-o"></i> {{ trans('player.player') }}</th>
                                    <th class="table-events" v-for="event in events"><i class="fa fa-calendar-o"></i> @{{ event.name }} at @{{ event.date | moment }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(player, index) in players">
                                    {{-- <td align="center"><i :class="playerClass(player)" class="fa" style="font-size: 22px"></i></td> --}}
                                    <td>@{{ player.first_name + ' ' + player.last_name }}</td>
                                    <td v-for="event in events">
                                        <span :class="{editable: isOwner}" v-if="hasAssignment(player, event)" :data-pk="player.id" :data-name="event.id" data-url="assignments" v-text="hasAssignment(player, event)"></span>

                                        <span :class="{editable: isOwner, 'text-muted': true}" data-value="Snacks and drinks" :data-pk="player.id" :data-name="event.id" data-url="assignments" v-else>{{ trans('assignment.default') }}</span>
                                    </td>
                                </tr>
                                <tr v-if="players.length == 0">
                                    <td :colspan="events.length + 1">
                                        {{ trans('lineup.not_available_players') }} <a href="{{ route('availability.index', $team->id) }}" title="">{{ trans('lineup.check_availability') }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
<!-- END Main container -->
    <div class="modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" @click="closeModal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('assignment.upcoming_events') }}</h4>
            </div>
            <div class="modal-body">
                  <ul>
                      <li v-for="event in events"><a :href="`/teams/${form.team_id}/assignments/${event.id}`">@{{ event.name }} - @{{ event.date | eMoment }}</a></li>
                  </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" @click="closeModal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('scripts')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    <script src="{{ asset('assets/vendors/sweetalert/js/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/x-editable/js/bootstrap-editable.js') }}" ></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script>
        const storage = {
            team_id: '{{ $team->id }}',
            csrf_token: '{{ csrf_token() }}',
            isOwner: {{ $team->isOwner(\Auth::id()) }}
        }
    </script>
    <script src="{{ mix('/js/assignments.js') }}" ></script>
@endsection
