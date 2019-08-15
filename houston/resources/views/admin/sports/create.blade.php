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
                Sports
                <small><i class="fa fa-plus"></i> Add Sport</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                        <div class="field">
                            <div class="f-col" v-for="row in rows">
                                <div class="f-row" v-for="col in cols">
                                    <span class="position" v-if="findPosition(col+row)" v-text="findPosition(col+row)"></span>
                                    <br>
                                    <span class="player" v-if="findPlayer(col+row)" v-html="findPlayer(col+row)"></span>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-Teal">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-plus"></i> Add new Sport</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::open(['method' => 'POST', 'route' => 'admin.sports.store', 'files' => true]) !!}
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
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="description">
                                            <i class="fa fa-folder-open"></i> Description
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 4]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="logo">
                                            <i class="fa fa-picture-o"></i> Sport Logo
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        {!! Form::file('logo', ['accept' => 'image/*']) !!}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-plus"></i>
                                        Save
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
