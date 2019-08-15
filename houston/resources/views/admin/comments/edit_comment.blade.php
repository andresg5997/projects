@extends('admin.index')

@section('style')
@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Comments
                <small>Edit Comment</small>
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
                            <h3 class="box-title"><i class="fa fa-edit"></i> Edit Comment in media | <span
                                        style="color:#3c8dbc">{{$comment->media->title}}</span></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::model($comment,['method'=>'PATCH','route'=>['comments.update',$comment->id]]) !!}
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
                                @if (session()->has('flash_notification.message'))
                                    <div class="alert alert-{{ session('flash_notification.level') }}">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        {!! session('flash_notification.message') !!}
                                    </div>
                                @endif
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="name">
                                            <i class="fa fa-folder-open"></i> Comment Body
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::textarea('body',null,['class'=>'form-control']) !!}
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

@endsection