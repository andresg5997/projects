@extends('layouts.app', ['title' => 'Edit '.$media->title])

@section('styles')
<!-- Select2 -->
{!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
{!! Html::style('assets/admin/assets/plugins/tag-input/bootstrap-tagsinput.css') !!}
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card" style="padding: 0px 25px">
                    <div class="card-header">
                        <h6>Edit Media</h6>
                    </div>
                    <div class="card-block">
                        @include('errors.list')
                        @include('components.flash_notification')
                        <!-- form start -->
                        {!! Form::model($media, ['method'=>'PATCH', 'route' => ['manage.update', $media->id], 'files' => true]) !!}
                            <div class="row">
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
                                                <i style="font-size:16px;" class="fa fa-link"></i> Private?
                                            </label>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <label class="checkbox-inline">
                                                        <input name="private" type="checkbox" {{ ($private == 1) ? 'checked' : '' }}> Private? (It can only be found by sharing the URL)
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-group">
                                                    <label class="checkbox-inline">
                                                        <input name="anonymous" type="checkbox" {{ ($anonymous == 1) ? 'checked' : '' }}> Anonymous? (Your username won't be displayed)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
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
                            </div>

                            <div class="row">
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
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <label for="tag_list">
                                                <i style="font-size:16px;" class="fa fa-hashtag"></i> Tags <span class="small">(separated by comma)</span>
                                            </label>
                                        </div>
                                        <div class="panel-body">
                                        <div class="form-group">
                                            <input data-role="tagsinput" id="" type="text" value="{{ $tags }}" name="tags" class="form-control bootstrap-tagsinput input-lg" placeholder="Tags">
                                        </div><!-- /.form-group -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <button type="submit" class="btn bg-default btn-app"><i class="fa fa-floppy-o"></i> Save Update </button>
                            </div>
                        {!! Form::close() !!}
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div><!-- /.row -->
    </div><!-- /.content-wrapper -->
@endsection
@section('scripts')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/tag-input/bootstrap-tagsinput.js') !!}
    <script src="http://cdn.ckeditor.com/4.6.2/full-all/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('editor1');
        });
    </script>
@endsection
