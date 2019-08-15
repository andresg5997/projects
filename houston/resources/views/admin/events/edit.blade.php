@extends('admin.index')

@section('style')
    <!-- Select2 -->
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') !!}

@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Events
                <small>Edit Event</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-Teal">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-edit"></i> Edit Event | <span
                                        style="color:#3c8dbc">{{$event->name}}</span></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::model($event, ['method' => 'PATCH', 'route' => ['admin.events.update', $event->id], 'files' => true]) !!}
                        @if (session()->has('flash_notification.message'))
                            <div class="alert alert-{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                {!! session('flash_notification.message') !!}
                            </div>
                        @endif
                        @if ( $errors->any() )
                            <div class="col-md-12" style="margin-top:15px;">
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                    <ul>
                                        @foreach ( $errors->all() as $error )
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="name">
                                            <i class="fa fa-folder-open"></i> Name
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="team_1">
                                            <i class="fa fa-flag"></i> Team
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="team_1" class="form-control">
                                                @foreach($teams as $team)
                                                    <option {!! ($team->id === $event->teams[0]->id) ? 'selected' : '' !!} value="{{ $team->id }}">{{ $team->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="sport_id">
                                            <i class="fa fa-soccer-ball-o"></i> Sport
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="sport_id" class="form-control">
                                                @foreach($sports as $sport)
                                                    <option {!! ($sport->id === $event->sport->id) ? 'selected' : '' !!} value="{{ $sport->id }}">{{ $sport->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="type_id">
                                            <i class="fa fa-users"></i> Event Type
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="type_id" class="form-control">
                                                @foreach($types as $type)
                                                    <option {!! ($type->id === $event->type->id) ? 'selected' : '' !!} value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="team_2">
                                            <i class="fa fa-flag"></i> Opponent team
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('team_2', $event->teams[0]->pivot->team_2, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="goals_1">
                                            <i class="fa fa-flag"></i> Team Goals
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('goals_1', $event->teams[0]->pivot->goals_1, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="goals_1">
                                            <i class="fa fa-flag"></i> Opponent Team Goals
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('goals_2', $event->teams[0]->pivot->goals_2, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-save"></i>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        {!! Form::close() !!}

                        <hr>

                    </div><!-- /.box -->


                </div><!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">
                    <!-- Horizontal Form -->
                </div>
            </div>   <!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection()
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js') !!}
    <script>
        $('.icon').iconpicker();

    </script>
@endsection
