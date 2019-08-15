@extends('admin.index')

@section('style')
    <!-- Select2 -->
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/tag-input/bootstrap-tagsinput.css') !!}
@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Media
                <small>Edit</small>
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
                            <h3 class="box-title"><i class="fa fa-edit"></i> Edit
                                Media: {{ str_limit($media->title, 50) }}</h3>
                        </div><!-- /.box-header -->

                    @include('errors.list')

                    <!-- form start -->
                        {!! Form::model($media, ['method'=>'PATCH', 'route' => ['medias.update', $media->id], 'files' => true]) !!}
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="category_id">
                                            <i class="fa fa-folder-open"></i> Post Category
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::select('category_id', $categories, null,
                                            ['class' => 'form-control', 'data-placeholder' => 'Select Category', 'style' => 'width: 100%;']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="slug">
                                            <i style="font-size:16px;" class="fa fa-link"></i> Media Slug
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="title">
                                            <i style="font-size:16px;" class="fa fa-list"></i> Media Title
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
                                        <label for="body">
                                            <i style="font-size:16px;" class="fa fa-list"></i> Media Content
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::textarea('body', null, ['class' => 'form-control', 'id' => 'editor1', 'row' => '10', 'cols' => '80']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="tag_list">
                                            <i style="font-size:16px;" class="fa fa-hashtag"></i> Tags
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <input data-role="tagsinput" id="" type="text" value="{{ $tags }}"
                                                   name="tags" class="form-control bootstrap-tagsinput input-lg"
                                                   placeholder="Tags">
                                        </div><!-- /.form-group -->
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-floppy-o"></i>
                                        Save Update
                                    </button>
                                </div>
                            </div><!-- /.box-body -->
                            {!! Form::close() !!}

                        </div><!-- /.box -->
                    </div><!--/.col (left) -->
                </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection()
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/tag-input/bootstrap-tagsinput.js') !!}
    <script src="http://cdn.ckeditor.com/4.6.2/full-all/ckeditor.js"></script>
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('editor1');
        });
    </script>
@endsection
