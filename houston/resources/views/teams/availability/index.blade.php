@extends('layouts.app', ['title' => trans('player.title_index')])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <style type="text/css" media="screen">
        select {
            font-family: FontAwesome, sans-serif;
        }
    </style>
@endsection

@section('content')
<div class="grass-bg">
    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center" style="margin-top: 40px">
            <ol class="breadcrumb">
              <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
              <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
              <li class="active">{{ trans('breadcrumb.availability') }}</li>
            </ol>
        </div>
    </div>

    <div id="app">
        <main class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ trans('availability.availability') }}</h6>
                        </div>
                        <div class="card-block table-overflow-x">
                            <h3 v-if="events.length === 0">{{ trans('availability.no_upcoming_events') }}</h3>
                            <table class="table table-bordered table-striped" style="margin: 20px" v-else>
                                <thead>
                                    <tr>
                                        <th class="table-events"><i class="fa fa-user-o"></i> {{ trans('player.player') }}</th>
                                        <th class="table-events" v-for="event in events"><i class="fa fa-calendar-o"></i> @{{ event.name }} at @{{ event.date | moment }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(player, index) in players">
                                        {{-- <td align="center"><i :class="playerClass(player)" class="fa" style="font-size: 22px"></i></td> --}}
                                        <td>
                                            @{{ player.first_name + ' ' + player.last_name }}
                                            @if($team->isOwner(\Auth::id()))
                                                <p>
                                                    <a href="#!" @click="markAllGames(player)"><b>Mark all available</b> <i class="fa fa-check"></i></a>
                                                </p>
                                            @endif
                                        </td>
                                        {{-- <td v-for="event in events">
                                            <select :value="eventStatus(player, event)" @change="changeStatus(player, event.id, $event)" class="form-control" :disabled="isDisabled">
                                                <option value="1">Not available</option>
                                                <option value="2">Maybe</option>
                                                <option value="3">Available</option>
                                            </select>
                                        </td> --}}
                                        <td v-for="event in events" class="availability-td">
                                            <div class="btn-group" v-if="isOwner">
                                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa" :class="eventClass(player, event)"></i><span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <li v-for="status in statuses">
                                                        <a style="text-align:center" @click="changeStatus(player, event.id, status.id)"><i class="fa" :class="status.classes"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div v-else>
                                                <div v-if="isParent(player)">
                                                    <div class="btn-group">
                                                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa" :class="eventClass(player, event)"></i><span class="caret"></span></a>
                                                        <ul class="dropdown-menu">
                                                            <li v-for="status in statuses">
                                                                <a style="text-align:center" @click="changeStatus(player, event.id, status.id)"><i class="fa" :class="status.classes"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <i v-else class="fa" :class="eventClass(player, event)"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="players.length === 0">
                                        <td colspan="6">{{ trans('player.no_players') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <!-- END Main container -->
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
            isOwner: {{ $team->isOwner(\Auth::id()) }},
            user_email: '{{ \Auth::user()->email }}'
        }
    </script>
    <script src="{{ mix('/js/availability.js') }}" ></script>
@endsection
