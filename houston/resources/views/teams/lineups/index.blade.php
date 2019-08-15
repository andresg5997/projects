@extends('layouts.app', ['title' => trans('lineup.title')])

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
                <li class="active">{{ trans('breadcrumb.lineup') }}</li>
            </ol>
        </div>
    </div>

    <div id="app">
        <main class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6><b>{{ trans('assignment.next_event') }}</b>:  <a :href="`/teams/${form.team_id}/events/${event.id}`" title="">@{{ event.name }}</a></h6>
                            {{-- <div class="pull-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#historyModal"><i class="fa fa-history"></i> {{ trans('assignment.event_history') }}</button>
                            </div> --}}
                        </div>
                        <div class="card-block clearfix">
                            <div class="col-md-12" v-if="lineups.length === 0">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('header.availability') }}</th>
                                            <th><i class="fa fa-id-badge"></i> {{ trans('player.name') }}</th>
                                            <th><i class="fa fa-cog"></i> {{ trans('lineup.mode') }}</th>
                                            <th><i class="fa fa-flag"></i> {{ trans('lineup.initial_position') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(player, index) in form.players">
                                            <td align="center"><i :class="playerClass(player)" class="fa" style="font-size: 22px"></i></td>
                                            <td>@{{ player.first_name + ' ' + player.last_name }}</td>
                                            <td>
                                                <select v-model="player.mode">
                                                    <option value="0">{{ trans('lineup.rotate') }}</option>
                                                    {{-- <option value="1">{{ trans('lineup.fixed') }}</option> --}}
                                                </select>
                                            </td>
                                            <td>
                                                <select v-model="player.position">
                                                    <option value="0">{{ trans('lineup.select_position') }}</option>
                                                    <option v-for="position in positions" :value="{name: position.name, grid: position.position}">@{{ position.name }}</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr v-if="form.players.length == 0">
                                            <td colspan="6" align="center">{{ trans('lineup.not_available_players') }} <a href="{{ route('availability.index', $team->id) }}" title="">{{ trans('lineup.check_availability') }}</a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <th>
                                                How many line ups do you need?
                                            </th>
                                            <td align="center">
                                                <label>@{{ form.quantity }}<input name="quantity" type="range" min="1" max="15" v-model="form.quantity"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td align="center">
                                                <button class="btn btn-grad" @click="submit" :disabled="form.players.length == 0">Create Line Ups!</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <template v-else>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <field-horizontal :lineup="lineup"></field-horizontal>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px">
                                    <div class="col-md-10 col-md-offset-1">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th><i class="fa fa-id-badge"></i> {{ trans('player.name') }}</th>
                                                    <th><i class="fa fa-cog"></i> {{ trans('lineup.mode') }}</th>
                                                    {{-- <th><i class="fa fa-flag"></i> {{ trans('lineup.position') }}</th> --}}
                                                    <th v-for="(lineup, index) in lineups"><a @click="setLineup(lineup.line_up)" href="#!">L@{{ index+1 }}</a></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(player, index) in lineups[0].line_up">
                                                    <td>@{{ player.first_name + ' ' + player.last_name }}</td>
                                                    <td>
                                                        <span v-if="player.mode == 0">Rotative</span>
                                                        <span v-else>Fixed</span>
                                                    </td>
                                                    <td v-for="lineup in lineups">
                                                        <select @change="updatePosition(player, lineup.id, $event)" v-model="lineup.line_up[index].position">
                                                            <option v-for="position in sport.positions" :value="position.name">
                                                                @{{ position.name }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
    {{--                             <div class="row" v-for="lineup in lineups" style="margin-bottom: 20px">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th><i class="fa fa-id-badge"></i> {{ trans('player.name') }}</th>
                                                    <th><i class="fa fa-cog"></i> {{ trans('lineup.mode') }}</th>
                                                    <th><i class="fa fa-flag"></i> {{ trans('lineup.position') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="line in lineup.line_up">
                                                    <td>@{{ line.first_name + ' ' + line.last_name }}</td>
                                                    <td>
                                                        <span v-if="line.mode == 0">Rotative</span>
                                                        <span v-else>Fixed</span>
                                                    </td>
                                                    <td>@{{ line.position }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <field :lineup="lineup.line_up"></field>
                                    </div>
                                </div> --}}
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <!-- END Main container -->
        {{-- <div class="modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('assignment.upcoming_events') }}</h4>
                </div>
                <div class="modal-body">
                      <ul>
                          <li v-for="event in events"><a href="#!" @click="setEvent(event)">@{{ event.name }} - @{{ event.date | eMoment }}</a></li>
                      </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="closeModal">Close</button>
                </div>
            </div>
        </div> --}}
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
            csrf_token: '{{ csrf_token() }}'
        }
    </script>
    <script src="{{ mix('/js/lineup.js') }}" ></script>
@endsection
