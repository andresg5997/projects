@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    {!! Html::style('assets/admin/assets/plugins/jquery-ui/jquery.ui.theme.css') !!}
@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Appearance
                <small>Themes</small>
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
                            <h3 class="box-title">Theme Options</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::open(['route'=>'admin.appearance.themes.update']) !!}
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
                        {{ method_field('patch') }}
                        <div class="box-body">

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="slug">
                                            <i style="font-size:16px;" class="fa fa-list"></i> Choose your color theme
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {{ Form::select('theme', [
                                                'aqua' => 'Aqua',
                                                'blue' => 'Blue',
                                                'brown' => 'Brown',
                                                'gray' => 'Gray',
                                                'green' => 'Green',
                                                'orange' => 'Orange',
                                                'pink' => 'Pink',
                                                'purple' => 'Purple',
                                                'red' => 'Red',
                                                'teal' => 'Teal',
                                                'yellow' => 'Yellow'
                                            ], config('theme'), ['class'=>'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-plus"></i> Save
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
@endsection
@section('javascript')

@endsection
