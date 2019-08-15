@extends('layouts.app', ['title' => 'Schedule'])
@section('styles')
    <link href="{{ asset('assets/vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/fullcalendar/css/fullcalendar.print.css') }}" rel="stylesheet"  media='print' type="text/css">
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9Gt7MivOrZF8Wbwz9_r1Fq2x_TOQLv7k&libraries=places"></script>
    <style>

    </style>
@endsection

@section('content')
{{-- Modal for creating event --}}

<div class="grass-bg">
    <div id="app">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center" style="margin-top: 40px">
                <ol class="breadcrumb">
                  <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
                  <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
                  <li class="active">{{ trans('breadcrumb.schedule') }}</li>
                </ol>
            </div>
        </div>
        @if($team->isOwner(Auth::id()))
            <div class="modal" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{ trans('schedule.create_event') }}</h4>
                        </div>
                        <div class="modal-body">
                            <form id="eventForm" method="POST" action="{{ route('schedule.postStore', $team->id) }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('type_id', trans('event.event_type')) !!}<br>
                                        <select name="type_id" class="form-control" v-model="form.type_id" @change="checkTypeId($event)">
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" v-if="form.type_id == 1">
                                        <div class="form-group">
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated fadeIn"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <div class="form-group">
                                                    {!! Form::label('enemy_team', trans('event.form_team_2')) !!}<br>
                                                    {!! Form::text('enemy_team', null, ['class' => 'form-control', 'v-model' => 'form.enemy_team', 'v-on:keyup' => 'changeName($event)']) !!}
                                                </div>
                                            </transition>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.enemy_team" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('name', trans('event.event_name')) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'form.name', 'placeholder' => trans('schedule.name_event')]) !!}
                                            <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated shake"
                                        leave-active-class="animated fadeOut"
                                        >
                                            <p v-if="errors.name" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                        </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="form.type_id == 1">
                                        {!! Form::label('uniform', trans('event.uniform')) !!}
                                        <select name="uniform" v-model="form.uniform" class="form-control">
                                            <option value="0">Home</option>
                                            <option value="1">Visitor</option>
                                        </select>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input id="location_name" type="hidden" name="location_name">
                                            <input id="location_url" type="hidden" name="location_url">
                                            {!! Form::label('location_id', trans('event.location')) !!}<br>
                                            <input type="text" id="googlePlaces" class="form-control">
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <span class="text-danger" v-if="errors.location_name">{{ trans('schedule.field_required') }}</span>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('season', trans('event.season')) !!}
                                            {!! Form::text('season', null, ['class' => 'form-control', 'placeholder' => trans('event.season_placeholder'), 'v-model' => 'form.season']) !!}
                                        </div>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('date', trans('schedule.date_time')) !!}<br>
                                            <div class='input-group date' id='datepicker'>
                                                <input name="date" id="date" type='text' class="form-control" value="{{ date('m/d/y', strtotime('today')) }}" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.date" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('time', trans('schedule.time')) !!}<br>
                                            <select name="time" id="time" class="form-control" @change="timeChanged" v-model="form.time">
                                                <option value="5:00 AM">05:00 AM</option>
                                                <option value="5:30 AM">05:30 AM</option>
                                                <option value="6:00 AM">06:00 AM</option>
                                                <option value="6:30 AM">06:30 AM</option>
                                                <option value="7:00 AM">07:00 AM</option>
                                                <option value="7:30 AM">07:30 AM</option>
                                                <option value="8:00 AM">08:00 AM</option>
                                                <option value="8:30 AM">08:30 AM</option>
                                                <option value="9:00 AM">09:00 AM</option>
                                                <option value="9:30 AM">09:30 AM</option>
                                                <option value="10:00 AM">10:00 AM</option>
                                                <option value="10:30 AM">10:30 AM</option>
                                                <option value="11:00 AM">11:00 AM</option>
                                                <option value="11:30 AM">11:30 AM</option>
                                                <option value="12:00 PM">12:00 PM</option>
                                                <option value="12:30 PM">12:30 PM</option>
                                                <option value="1:00 PM">01:00 PM</option>
                                                <option value="1:30 PM">01:30 PM</option>
                                                <option value="2:00 PM">02:00 PM</option>
                                                <option value="2:30 PM">02:30 PM</option>
                                                <option value="3:00 PM">03:00 PM</option>
                                                <option value="3:30 PM">03:30 PM</option>
                                                <option value="4:00 PM">04:00 PM</option>
                                                <option value="4:30 PM">04:30 PM</option>
                                                <option value="5:00 PM">05:00 PM</option>
                                                <option value="5:30 PM">05:30 PM</option>
                                                <option value="6:00 PM">06:00 PM</option>
                                                <option value="6:30 PM">06:30 PM</option>
                                                <option value="7:00 PM">07:00 PM</option>
                                                <option value="7:30 PM">07:30 PM</option>
                                                <option value="8:00 PM">08:00 PM</option>
                                                <option value="8:30 PM">08:30 PM</option>
                                                <option value="9:00 PM">09:00 PM</option>
                                            </select>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.time" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('endDate', trans('schedule.end_date_time')) !!}<br>
                                            <div class='input-group date' id='endDatepicker'>
                                                <input name="endDate" id="endDate" type='text' class="form-control" value="{{ date('m/d/y', strtotime('today')) }}" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.endDate" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('endTime', trans('schedule.endTime')) !!}<br>
                                            <select name="endTime" id="endTime" class="form-control" v-model="form.endTime">
                                                <option value="5:00 AM">05:00 AM</option>
                                                <option value="5:30 AM">05:30 AM</option>
                                                <option value="6:00 AM">06:00 AM</option>
                                                <option value="6:30 AM">06:30 AM</option>
                                                <option value="7:00 AM">07:00 AM</option>
                                                <option value="7:30 AM">07:30 AM</option>
                                                <option value="8:00 AM">08:00 AM</option>
                                                <option value="8:30 AM">08:30 AM</option>
                                                <option value="9:00 AM">09:00 AM</option>
                                                <option value="9:30 AM">09:30 AM</option>
                                                <option value="10:00 AM">10:00 AM</option>
                                                <option value="10:30 AM">10:30 AM</option>
                                                <option value="11:00 AM">11:00 AM</option>
                                                <option value="11:30 AM">11:30 AM</option>
                                                <option value="12:00 PM">12:00 PM</option>
                                                <option value="12:30 PM">12:30 PM</option>
                                                <option value="1:00 PM">01:00 PM</option>
                                                <option value="1:30 PM">01:30 PM</option>
                                                <option value="2:00 PM">02:00 PM</option>
                                                <option value="2:30 PM">02:30 PM</option>
                                                <option value="3:00 PM">03:00 PM</option>
                                                <option value="3:30 PM">03:30 PM</option>
                                                <option value="4:00 PM">04:00 PM</option>
                                                <option value="4:30 PM">04:30 PM</option>
                                                <option value="5:00 PM">05:00 PM</option>
                                                <option value="5:30 PM">05:30 PM</option>
                                                <option value="6:00 PM">06:00 PM</option>
                                                <option value="6:30 PM">06:30 PM</option>
                                                <option value="7:00 PM">07:00 PM</option>
                                                <option value="7:30 PM">07:30 PM</option>
                                                <option value="8:00 PM">08:00 PM</option>
                                                <option value="8:30 PM">08:30 PM</option>
                                                <option value="9:00 PM">09:00 PM</option>
                                                <option value="9:30 PM">09:30 PM</option>
                                                <option value="10:00 PM">10:00 PM</option>
                                            </select>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.endTime" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ trans('schedule.repeat') }}</label><br>
                                            <select v-model="form.frequency" name="frequency">
                                                <option value="">No repeat</option>
                                                <option value="week">Weekly</option>
                                                <option value="month">Monthly</option>
                                                <option value="year">Yearly</option>
                                            </select>
                                            <span v-if="form.frequency != '' && form.frequency != 'times'">
                                                every <input name="interval" type="number" v-model="form.interval" min="1" max="4"> <span v-text="form.frequency"></span>(s) until <input type="date" v-model="form.repeatDate" name="repeatDate"> <span v-if="form.frequency == 'week' || form.frequency == 'month'">on</span>
                                                <transition
                                                name="custom-classes-transition"
                                                enter-active-class="animated shake"
                                                leave-active-class="animated fadeOut"
                                                >
                                                    <p v-if="errors.repeatDate" class="text-danger">{{ trans('schedule.repeatDate_required') }}</p>
                                                </transition>
                                                <div v-if="form.frequency == 'week'">
                                                    <br>
                                                    <label><input name="days[]" type="checkbox" value="sunday" v-model="form.days">Sunday</label>
                                                    <label><input name="days[]" type="checkbox" value="monday" v-model="form.days">Monday</label>
                                                    <label><input name="days[]" type="checkbox" value="tuesday" v-model="form.days">Tuesday</label>
                                                    <label><input name="days[]" type="checkbox" value="wednesday" v-model="form.days">Wednesday</label>
                                                    <br>
                                                    <label><input name="days[]" type="checkbox" value="thursday" v-model="form.days">Thursday</label>
                                                    <label><input name="days[]" type="checkbox" value="friday" v-model="form.days">Friday</label>
                                                    <label><input name="days[]" type="checkbox" value="saturday" v-model="form.days">Saturday</label>
                                                </div>
                                                <div v-if="form.frequency == 'month'">
                                                    <br>
                                                    <div class="form-group">
                                                        <input name="monthType" type="radio" value="" v-model="form.month.type">
                                                        the
                                                        <select name="position" :disabled="(form.month.type == '0') ? false : true" v-model="form.month.position">
                                                            <option value="1" selected="selected">First</option>
                                                            <option value="2">Second</option>
                                                            <option value="3">Third</option>
                                                            <option value="4">Fourth</option>
                                                        </select>
                                                        <select name="positionMonth" :disabled="(form.month.type == '0') ? false : true" v-model="form.month.positionMonth">
                                                            <option value="sunday" selected="selected">Sunday</option>
                                                            <option value="monday">Monday</option>
                                                            <option value="tuesday">Tuesday</option>
                                                            <option value="wednesday">Wednesday</option>
                                                            <option value="thursday">Thursday</option>
                                                            <option value="friday">Friday</option>
                                                            <option value="saturday">Saturday</option>
                                                        </select>
                                                        of the month
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="monthType" type="radio" value="1" v-model="form.month.type"> same date
                                                    </div>
                                                </div>
                                                <div v-if="form.frequency == 'year'">
                                                    <br>
                                                    <div class="form-group">
                                                        <input name="yearType" type="radio" v-model="form.year.type" value=""> same date
                                                    </div>
                                                </div>
                                            </span>
                                            <span v-if="form.frequency == 'times'"><input name="times" type="number" v-model="form.times" style="width: 40px; margin-left: 5px"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Form::label('notes', trans('event.notes')) !!}
                                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'v-model' => 'form.notes', 'rows' => '4']) !!}
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><input type="checkbox" name="setReminder" v-model="setReminder"> {{ trans('schedule.send_reminder') }}</label>
                                        <template v-if="setReminder">
                                            <input type="number" v-model="form.reminderDays" min="1" max="10"> <label>{{ trans('schedule.days_before') }}</label>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <a type="button" class="btn btn-default" @click="closeModal" data-dismiss="modal">Close</a>
                            <button type="button" class="btn btn-primary" :disabled="form.isLoading" @click="sendEvent">{{ trans('schedule.create_event') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="showModal" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@{{ event.name }}</h4>
              </div>
              <div class="modal-body">
                <p>
                    <b>{{ trans('schedule.date_time') }}</b>: @{{ event.date | date }} to @{{ event.end_date | time }}
                </p>
                <p>
                    <p>
                        <b>{{ trans('schedule.at') }}</b>: <a :href="event.location_url" title="">@{{ event.location_name }}</a>
                    </p>
                </p>
                <p v-if="event.notes">
                    <b>{{ trans('schedule.notes') }}</b>: @{{ event.notes }}
                </p>
                <h4>
                    {{ trans('schedule.download_calendar') }}
                </h4>
                <ul>
                    <li><a href="#" @click="exportEvent('google', event.id)">Google</a></li>
                    <li><a href="#" @click="exportEvent('yahoo', event.id)">Yahoo</a></li>
                    <li><a href="#" @click="exportEvent('ical', event.id)">iCal and Outlook</a></li>
                </ul>
              </div>
              <div class="modal-footer">
                @if($team->isOwner(Auth::id()))
                    <button type="button" class="btn btn-primary" @click="editEvent">{{ trans('schedule.edit') }}</button>
                @endif
                <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @if($team->isOwner(Auth::id()))
            <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="closeModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{ trans('schedule.edit') }}</h4>
                        </div>
                        <div class="modal-body">
                            <form id="updateEvent" method="POST" action="{{ route('schedule.updateEvent') }}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                <input type="hidden" name="event_id" :value="event.id">
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('type_id', trans('event.event_type')) !!}<br>
                                        <select name="type_id" class="form-control" v-model="event.type_id" @change="checkTypeId($event)">
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" v-if="event.type_id == 1">
                                        <div class="form-group">
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated fadeIn"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <div class="form-group">
                                                    {!! Form::label('enemy_team', trans('event.form_enemy_team')) !!}<br>
                                                    {!! Form::text('enemy_team', null, ['class' => 'form-control', 'v-model' => 'event.enemy_team', 'v-on:keyup' => 'changeName($event)']) !!}
                                                </div>
                                            </transition>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.enemy_team" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('name', trans('event.event_name')) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'event.name', 'placeholder' => trans('schedule.name_event')]) !!}
                                            <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated shake"
                                        leave-active-class="animated fadeOut"
                                        >
                                            <p v-if="errors.name" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                        </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="event.type_id != 2">
                                        {!! Form::label('uniform', trans('event.uniform')) !!}
                                        <select name="uniform" v-model="event.uniform" class="form-control">
                                            <option value="">Home</option>
                                            <option value="1">Visitor</option>
                                        </select>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input id="location_name" type="hidden" name="location_name">
                                            <input id="location_url" type="hidden" name="location_url">
                                            {!! Form::label('location_id', trans('event.location')) !!}<br>
                                            <input type="text" id="googlePlaces" class="form-control">
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <span class="text-danger" v-if="errors.location_name">{{ trans('schedule.field_required') }}</span>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('season', trans('event.season')) !!}
                                            {!! Form::text('season', null, ['class' => 'form-control', 'placeholder' => trans('event.season_placeholder'), 'v-model' => 'event.season']) !!}
                                        </div>
                                    </div>
                                </div> {{-- End Row  --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('date', trans('schedule.date_time')) !!}<br>
                                            <div class='input-group date' id='datepicker'>
                                                <input name="date" id="date" type='text' class="form-control" value="{{ date('m/d/y', strtotime('today')) }}" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.date" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('time', trans('schedule.time')) !!}<br>
                                            <select name="time" id="time" class="form-control" @change="timeChanged" v-model="event.time">
                                                <option value="5:00 AM">05:00 AM</option>
                                                <option value="5:30 AM">05:30 AM</option>
                                                <option value="6:00 AM">06:00 AM</option>
                                                <option value="6:30 AM">06:30 AM</option>
                                                <option value="7:00 AM">07:00 AM</option>
                                                <option value="7:30 AM">07:30 AM</option>
                                                <option value="8:00 AM">08:00 AM</option>
                                                <option value="8:30 AM">08:30 AM</option>
                                                <option value="9:00 AM">09:00 AM</option>
                                                <option value="9:30 AM">09:30 AM</option>
                                                <option value="10:00 AM">10:00 AM</option>
                                                <option value="10:30 AM">10:30 AM</option>
                                                <option value="11:00 AM">11:00 AM</option>
                                                <option value="11:30 AM">11:30 AM</option>
                                                <option value="12:00 PM">12:00 PM</option>
                                                <option value="12:30 PM">12:30 PM</option>
                                                <option value="1:00 PM">01:00 PM</option>
                                                <option value="1:30 PM">01:30 PM</option>
                                                <option value="2:00 PM">02:00 PM</option>
                                                <option value="2:30 PM">02:30 PM</option>
                                                <option value="3:00 PM">03:00 PM</option>
                                                <option value="3:30 PM">03:30 PM</option>
                                                <option value="4:00 PM">04:00 PM</option>
                                                <option value="4:30 PM">04:30 PM</option>
                                                <option value="5:00 PM">05:00 PM</option>
                                                <option value="5:30 PM">05:30 PM</option>
                                                <option value="6:00 PM">06:00 PM</option>
                                                <option value="6:30 PM">06:30 PM</option>
                                                <option value="7:00 PM">07:00 PM</option>
                                                <option value="7:30 PM">07:30 PM</option>
                                                <option value="8:00 PM">08:00 PM</option>
                                                <option value="8:30 PM">08:30 PM</option>
                                                <option value="9:00 PM">09:00 PM</option>
                                            </select>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.time" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('endDate', trans('schedule.end_date_time')) !!}<br>
                                            <div class='input-group date' id='endDatepicker'>
                                                <input name="endDate" id="endDate" type='text' class="form-control" value="{{ date('m/d/y', strtotime('today')) }}" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.endDate" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('endTime', trans('schedule.endTime')) !!}<br>
                                            <select name="endTime" id="endTime" class="form-control" v-model="event.endTime">
                                                <option value="5:00 AM">05:00 AM</option>
                                                <option value="5:30 AM">05:30 AM</option>
                                                <option value="6:00 AM">06:00 AM</option>
                                                <option value="6:30 AM">06:30 AM</option>
                                                <option value="7:00 AM">07:00 AM</option>
                                                <option value="7:30 AM">07:30 AM</option>
                                                <option value="8:00 AM">08:00 AM</option>
                                                <option value="8:30 AM">08:30 AM</option>
                                                <option value="9:00 AM">09:00 AM</option>
                                                <option value="9:30 AM">09:30 AM</option>
                                                <option value="10:00 AM">10:00 AM</option>
                                                <option value="10:30 AM">10:30 AM</option>
                                                <option value="11:00 AM">11:00 AM</option>
                                                <option value="11:30 AM">11:30 AM</option>
                                                <option value="12:00 PM">12:00 PM</option>
                                                <option value="12:30 PM">12:30 PM</option>
                                                <option value="1:00 PM">01:00 PM</option>
                                                <option value="1:30 PM">01:30 PM</option>
                                                <option value="2:00 PM">02:00 PM</option>
                                                <option value="2:30 PM">02:30 PM</option>
                                                <option value="3:00 PM">03:00 PM</option>
                                                <option value="3:30 PM">03:30 PM</option>
                                                <option value="4:00 PM">04:00 PM</option>
                                                <option value="4:30 PM">04:30 PM</option>
                                                <option value="5:00 PM">05:00 PM</option>
                                                <option value="5:30 PM">05:30 PM</option>
                                                <option value="6:00 PM">06:00 PM</option>
                                                <option value="6:30 PM">06:30 PM</option>
                                                <option value="7:00 PM">07:00 PM</option>
                                                <option value="7:30 PM">07:30 PM</option>
                                                <option value="8:00 PM">08:00 PM</option>
                                                <option value="8:30 PM">08:30 PM</option>
                                                <option value="9:00 PM">09:00 PM</option>
                                                <option value="9:30 PM">09:30 PM</option>
                                                <option value="10:00 PM">10:00 PM</option>
                                            </select>
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut"
                                            >
                                                <p v-if="errors.endTime" class="text-danger">{{ trans('schedule.field_required') }}</p>
                                            </transition>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Form::label('notes', trans('event.notes')) !!}
                                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'v-model' => 'event.notes', 'rows' => '4']) !!}
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><input type="checkbox" name="setReminder" v-model="setReminder"> {{ trans('schedule.send_reminder') }}</label>
                                        <template v-if="setReminder">
                                            <input type="number" v-model="event.reminderDays" min="1" max="10"> <label>{{ trans('schedule.days_before') }}</label>
                                        </template>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <a type="button" class="btn btn-default" @click="closeModal" data-dismiss="modal">Close</a>
                            <button type="button" class="btn btn-primary" :disabled="event.isLoading" @click="updateEvent">{{ trans('schedule.update_event') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="content" style="margin-top: 20px; margin-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Schedule</h6>
                            @if($team->isOwner(Auth::id()))
                                <button type="button" class="pull-right btn btn-primary btn-grad" data-toggle="modal" data-target="#eventModal">
                                    {{ trans('schedule.create_event') }}
                                </button>
                            @endif
                        </div>
                        <div class="card-block">
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-10 col-md-offset-1">
                                    {!! $calendar->calendar() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/fullcalendar/js/fullcalendar.min.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/plugins/vue.js') }}"></script>
    <script src="{{ asset('assets/plugins/axios.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/sweetalert/js/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        const app = new Vue({
            el: '#app',
            // components: { VueGoogleAutocomplete },
            data: {
                setReminder: false,
                user_id: {{ Auth::id() }},
                isLoading: false,
                types: {!! stripslashes($types) !!},
                teams: [],
                errors: {
                    name: false,
                    date: false,
                    endDate: false,
                    time: false,
                    endTime: false,
                    repeatDate: false,
                    enemy_team: false,
                    newLocation: false
                },
                form: {
                    location_url: '',
                    location_name: '',
                    name: 'vs ',
                    date: '',
                    time: '5:00 AM',
                    endDate: '',
                    endTime: '6:00 AM',
                    team_id: '{{ $team->id }}',
                    enemy_team: '',
                    uniform: '0',
                    notes: '',
                    type_id: '1',
                    reminderDays: '',
                    csrf_token: '{{ csrf_token() }}',
                    frequency: '',
                    interval: '1',
                    season: '',
                    times: 1,
                    repeatDate: '',
                    days: [],
                    month: {
                        type: '0',
                        position: '1',
                        positionMonth: 'sunday',
                        month: 'january'
                    },
                    year: {
                        type: '0',
                        month: 'january',
                        position: '1',
                        positionMonth: 'sunday'
                    },
                    address: ''
                },
                googleAddress: {
                    street_number: null,
                    street_name: null,
                    city: null,
                    state: null,
                    zipcode: null,
                    country: null,
                    url: null,
                    autocomplete: null
                },
                events: {
                },
                event: {
                    name: ''
                }
            },
            methods: {
                editEvent(){
                    this.closeModal()
                    $('#editModal').modal('show')
                    setTimeout(() => {
                    $('body').removeClass('modal-open').addClass('modal-open')

                    }, 500)
                },
                exportEvent(calendar, event_id){
                    var url = `/export/${calendar}/${event_id}`
                    var win = window.open(url, '_blank');
                    win.focus();
                },
                showEvent(id){
                    this.event = this.findEvent(id)
                    $('#showModal').modal('show')
                },
                findEvent(id){
                    return this.events.filter((event) => event.id === id)[0]
                },
                timeChanged(event){
                    let date = new Date()
                    let time = event.target.value.split(':')
                    let ampm = time[1].split(' ')[1]
                    let hours = parseInt(time[0])
                    if(ampm == 'PM'){
                        hours += 12
                    }
                    let minutes = parseInt(time[1])
                    console.log(minutes)
                    date.setHours(hours, minutes, 0)
                    date.setHours(date.getHours() + 1)

                    let endHours = date.getHours()
                    let endAmpm = 'AM'
                    if(endHours > 12) {
                        endHours -= 12
                        endAmpm = 'PM'
                    }
                    if(hours == 24) {
                        endAmpm = 'PM'
                    }
                    let endMinutes = date.getMinutes()
                    if(date.getMinutes() == 0){
                        endMinutes = '00'
                    }
                    let endTime = `${endHours}:${endMinutes} ${endAmpm}`
                    console.log(endTime)
                    this.form.endTime = endTime
                },
                changeName(event){
                    this.form.name = 'vs ' + event.target.value
                },
                checkTypeId(event){
                    if(parseInt(event.target.value) == 1){
                        this.form.name = 'vs '
                        return
                    }
                    let type = this.types.filter((type) => type.id === parseInt(event.target.value))[0]
                    this.form.name = type.name
                },
                dateSetter(date){
                    this.form.date = date
                    this.form.endDate = date

                    var d = new Date(date)
                    let day = ("" + (d.getDate() + 1)).slice(-2)
                    let month = ("" + (d.getMonth() + 1)).slice(-2);
                    date = (month + "/" + day +"/" + d.getFullYear())

                    $('#date').val(date)
                    $('#endDate').val(date)
                    $('#eventModal').modal('show')
                },
                closeModal(){
                    $('#eventModal').modal('hide');
                    $('#showModal').modal('hide');
                    $('.modal-backdrop.in').remove();
                },
                updateEvent() {
                    this.isLoading = true
                    // console.log('Sending')
                    this.errors = {}
                    swal('Event updated!', 'Page will be reloaded shortly...', 'success')
                            setTimeout(function() {
                                document.getElementById('updateEvent').submit()
                            }, 2000)
                    this.isLoading = false
                },
                sendEvent() {
                    this.isLoading = true
                    // console.log('Sending')
                    this.errors = {}
                    axios.post(`/teams/${this.form.team_id}/schedule`, this.form)
                        .then((res) => {
                            swal('Event saved!', 'Page will be reloaded shortly...', 'success')
                            setTimeout(function() {
                                document.getElementById('eventForm').submit()
                            }, 2000)
                        })
                        .catch((error) => {
                            app.errors = error.response.data
                            })
                    this.isLoading = false
                },
                getAddress(){
                    var place = this.googleAddress.autocomplete.getPlace()
                    this.form.location_name = place.name
                    this.form.location_url = place.url;
                    document.getElementById('location_name').value = place.name
                    document.getElementById('location_url').value = place.url

                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        switch (addressType) {
                            case 'street_number':
                                this.googleAddress.street_number = place.address_components[i]['short_name'];
                                break;
                            case 'route':
                                this.googleAddress.street_name = place.address_components[i]['short_name'];
                                break;
                            case 'locality':
                                this.googleAddress.city = place.address_components[i]['long_name'];
                                break;
                            case 'administrative_area_level_1':
                                this.googleAddress.state = place.address_components[i]['short_name'];
                                break;
                            case 'postal_code':
                                this.googleAddress.zipcode = place.address_components[i]['short_name'];
                                break;
                            case 'country':
                                this.googleAddress.country = place.address_components[i]['short_name'];
                                break;
                        }
                    }
                }
            },
            mounted() {
                axios.get(`/teams/${this.form.team_id}/teams`)
                    .then((res) => {
                        this.teams = res.data.teams
                    })
                    .catch((error) => console.log(error))

                axios.get(`/teams/${this.form.team_id}/getEvents`)
                    .then((res) => {
                        this.events = res.data.events
                    })
                    .catch((error) => console.log(error))

                    var input = document.getElementById('googlePlaces')
                    var options = {
                        types: ['geocode']
                    }
                    this.googleAddress.autocomplete = new google.maps.places.Autocomplete(input, options);
                    this.googleAddress.autocomplete.addListener('place_changed', this.getAddress);
            },
            filters: {
                eMoment(date){
                    return moment(date).format('ddd DD MMM YYYY [at] H:mm')
                },
                date(date){
                    return moment(date).format('MMM, DD, YYYY (ddd) [at] hh:mm')
                },
                time(date){
                    return moment(date).format('H:mm')
                }
            }
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
                useCurrent: true
            });
            $('#endDatepicker').datetimepicker({
                format: 'MM/DD/YYYY',
                useCurrent: false
            });

            $("#datepicker").on("dp.change", function (e) {
                app.form.date = $('#date').val()
            });
            $("#endDate").on("dp.change", function (e) {
                app.form.endDate = $('#endDate').val()
            });

            $('#timepicker').datetimepicker({
                format: 'LT'
            });
            $('#endTimepicker').datetimepicker({
                format: 'LT'
            });

            $("#timepicker").on("dp.change", function (e) {
                app.form.time = $('#time').val()
            });
            $("#endTimepicker").on("dp.change", function (e) {
                app.form.endTime = $('#endTime').val()
            });
        })
    </script>
    {!! $calendar->script() !!}
@endsection
