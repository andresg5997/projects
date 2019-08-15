@extends('admin.index', ['title' => $title])

@section('style')
    <!-- Select2 -->
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/css/switchery.min.css') !!}
    <style>
        .box-title{
            margin-bottom: 30px;
            margin-top: 30px;
        }
    </style>
@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Settings
                <small>{{ $title }}</small>
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
                            <h3 class="box-title">{{ $title }} Settings</h3>
                        </div><!-- /.box-header -->

                        <!-- form start -->
                        {!! Form::open(['route' => 'settings.'.str_replace(" ", ".", strtolower($title)).'.update', 'files' => true]) !!}
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

                            @yield('settings-content')

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
                </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/js/switchery.min.js') !!}
    <script>
        // Switchery plugin
        if ($('.js-switch').length) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html, { size: 'small' });
            });
        }

        if ($('.js-switch-big').length) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-big'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html);
            });
        }
    </script>

    @yield('scripts')

@endsection
