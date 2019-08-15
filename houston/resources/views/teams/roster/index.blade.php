@extends('layouts.app', ['title' => trans('player.title_index')])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
@endsection

@php
    $isOwner = $team->isOwner(Auth::id());
@endphp

@section('content')
<div class="grass-bg">
    <div class="container">
        @include('flash::message')
    </div>

    <div class="row">
        <div class="col-sm-12 d-flex justify-content-center" style="margin-top: 40px">
            <ol class="breadcrumb">
              <li><a href="/">{{ trans('breadcrumb.home') }}</a></li>
              <li><a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a></li>
              <li class="active">{{ trans('breadcrumb.roster') }}</li>
            </ol>
        </div>
    </div>

    <div id="app">
        <main class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ trans('breadcrumb.roster') }}</h6>
                            @if($isOwner)
                                <div class="pull-right" style="margin-right: 20px">
                                    <button class="btn btn-black" data-toggle="modal" data-target="#importModal">
                                        <i class="fa fa-upload"></i> {{ trans('player.import') }}
                                    </button>
                                    <button class="btn btn-grad" data-toggle="modal" data-target="#rosterModal">
                                        <i class="fa fa-plus"></i> {{ trans('player.register') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="card-block">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><i class="fa fa-flag"></i> {{ trans('player.player') }}</th>
                                        <th><i class="fa fa-user"></i> {{ trans('player.email') }} </th>
                                        <th><i class="fa fa-user"></i> {{ trans('player.parent_name') }} </th>
                                        <th><i class="fa fa-phone"></i> {{ trans('player.parent_phone') }} </th>
                                        @if($isOwner)
                                            <th><i class="fa fa-cog"></i> {{ trans('player.actions') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="player in players">
                                        <td align="center">
                                            <img v-if="player.picture" :src="'/storage/pictures/' + player.picture" width="60px">
                                            <div v-if="updatePicture.user_email === player.parent_email">
                                                <small><a href="#!" class="badge bg-info" data-toggle="modal" @click="setPlayerId(player.id)" data-target="#updatePictureModal">{{ trans('player.update_picture') }}!</a></small>
                                            </div>
                                        </td>
                                        <td v-text="player.first_name + ' ' + player.last_name"></td>
                                        <td><a :href="'mailto:'+ player.email" v-text="player.email"></a></td>
                                        <td v-text="player.parent_name"></td>
                                        <td v-text="player.phone"></td>
                                        @if($isOwner)
                                            <td align="center">
                                                <a title="{{ trans('player.view') }}" :href="'{{ route('roster.show', [$team->id, null]) }}/' + player.id" class="btn text-white btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                <button @click="playerEdit(player)" title="{{ trans('player.edit') }}" class="btn text-white btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                                                <button @click="deletePlayer(player.id)" title="{{ trans('player.delete') }}" class="btn text-white btn-danger btn-xs"><i class="fa fa-recycle"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr v-if="players.length == 0">
                                        <td colspan="8">{{ trans('player.no_players') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <!-- END Main container -->
    @if($isOwner)
        <div class="modal" id="rosterModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel" v-if="!editPlayer">{{ trans('player.add_player') }}</h4>
                        <h4 class="modal-title" id="myModalLabel" v-else>{{ trans('player.edit') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            {!! Form::open(['method' => 'POST', 'files' => true, 'id' => 'playerForm']) !!}
                            <div class="row">
                                <div class="col-md-10">
                                    <h5>{{ trans('player.player_info') }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('picture', trans('player.picture')) !!}
                                        <input name="picture" type="file" accept="image/*" @change="imageChanged">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <img src="" id="output" class="img-fluid">
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('first_name', trans('player.first_name')) !!}
                                            {!! Form::text('first_name', null, ['v-model' => 'form.first_name', 'class' => 'form-control', 'placeholder' => trans('player.first_name')]) !!}
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut">
                                            <div class="text-danger" v-if="errors.first_name">{{ trans('player.field_required') }}</div>
                                            </transition>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('last_name', trans('player.last_name')) !!}
                                            {!! Form::text('last_name', null, ['v-model' => 'form.last_name', 'class' => 'form-control', 'placeholder' => trans('player.last_name')]) !!}
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut">
                                            <div class="text-danger" v-if="errors.first_name">{{ trans('player.field_required') }}</div>
                                            </transition>
                                        </div>
                                     </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('teacher', trans('player.teacher')) !!}
                                            {!! Form::text('teacher', null, ['v-model' => 'form.teacher', 'class' => 'form-control', 'placeholder' => trans('player.teacher')]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('number', trans('player.number')) !!}
                                            {!! Form::number('number', null, ['class' => 'form-control', 'v-model' => 'form.number']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('gender', trans('player.gender')) !!}<br>
                                            {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'], null, ['v-model' => 'form.gender', 'class' => 'select2']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <h5>{{ trans('player.parent_info') }}</h5>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('parent_name', trans('player.parent_name')) !!}
                                            {!! Form::text('parent_name', null, ['v-model' => 'form.parent_name', 'class' => 'form-control', 'placeholder' => trans('player.parent_name')]) !!}
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut">
                                            <div class="text-danger" v-if="errors.parent_name">{{ trans('player.field_required') }}</div>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('email', trans('player.email')) !!}
                                            {!! Form::email('email', null, ['v-model' => 'form.email', 'class' => 'form-control', 'placeholder' => trans('player.email')]) !!}
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut">
                                            <div class="text-danger" v-if="errors.email">{{ trans('player.field_required') }}</div>
                                            </transition>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('phone', trans('player.phone')) !!}
                                            {!! Form::text('phone', null, ['v-model' => 'form.phone', 'class' => 'form-control', 'placeholder' => '(999) 999-9999', 'v-mask' => '"(###) ###-####"']) !!}
                                            <transition
                                            name="custom-classes-transition"
                                            enter-active-class="animated shake"
                                            leave-active-class="animated fadeOut">
                                            <div class="text-danger" v-if="errors.phone">{{ trans('player.field_required') }}</div>
                                            </transition>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-default" @click="closeModal()" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary" @click="storePlayer" v-if="!editPlayer">{{ trans('player.add_player') }}</button>
                        <button type="submit" class="btn btn-primary" @click="updatePlayer(form.id)" v-else>{{ trans('player.update') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="importModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('player.import_title') }}</h4>
                    </div>
                    <form action="{{ route('teams.importRoster', $team->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <p>
                                    <a href="{{ route('roster.template') }}">
                                        <b>{{ trans('player.import_body') }}{{ trans('player.import_here') }}</b>
                                    </a>.
                                </p>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="file" name="file"><br>
                                <p><strong>{{ trans('player.import_note') }}</strong><br><strong>{{ trans('player.import_required') }}</strong></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <a @click="closeModal()" data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ trans('player.import_file') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="modal" id="updatePictureModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('player.update_picture') }}</h4>
                </div>
                <form action="{{ route('roster.updatePicture', $team->id) }}" method="PUT" enctype="multipart/form-data" @submit.prevent="sendPicture">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newPicture">
                                {{ trans('player.new_picture') }}
                            </label>
                            <input name="newPicture" id="newPicture" type="file" accept="image/*" @change="newPicture($event)">
                        </div>
                    </div>
                    <div class="modal-footer">
                    <a @click="closeModal()" data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                    <button type="submit" class="btn btn-success">{{ trans('player.upload_picture') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

@section('scripts')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    <script src="{{ asset('assets/vendors/sweetalert/js/sweetalert2.js') }}" type="text/javascript"></script>
    <script>
        const storage = {
            team_id:    '{{ $team->id }}',
            csrf_token: '{{ csrf_token() }}',
            isOwner: {{ ($isOwner === true) ? 'true' : 'false' }},
            user_email: '{{ Auth::user()->email }}'
        }
    </script>
    <script src="{{ mix('js/roster.js') }}"></script>
@endsection
