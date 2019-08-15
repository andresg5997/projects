@extends('admin.index')

@section('style')
    <!-- Select2 -->
    {!! Html::style('public/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') !!}

@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Pages
                <small><i class="fa fa-plus"></i> Add Page</small>
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
                            <h3 class="box-title"><i class="fa fa-plus"></i> Add New Page</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        @if (! empty($footer))
                            {!! Form::open(['route' => 'footer-pages.store']) !!}
                            {{ Form::hidden('footer', 1) }}
                        @else
                            {!! Form::open(['route' => 'pages.store']) !!}
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
                                            <i class="fa fa-folder-open"></i> Page Name
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
                                        <label for="slug">
                                            <i style="font-size:16px;" class="fa fa-link"></i> Page Slug
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="order">
                                            <i style="font-size:16px;" class="fa fa-sort"></i> Page Order
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::number('order', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="order">
                                            <i style="font-size:16px;" class="fa fa-sort"></i> Parent ID (0 for create a parent)
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::number('parent', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="title">
                                            <i style="font-size:16px;" class="fa fa-list"></i> Page Title
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="content">
                                            <i style="font-size:16px;" class="fa fa-list"></i> Page Content
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'editor1', 'row' => '10', 'cols' => '80']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-plus"></i>
                                        Create
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
    {!! Html::script('assets/admin/assets/plugins/ckeditor/ckeditor.js') !!}
    {!! Html::script('assets/admin/assets/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js') !!}

    <script>
        CKEDITOR.replace('editor1');
        $('.icon').iconpicker();

    </script>
@endsection