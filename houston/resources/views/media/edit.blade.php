@extends('layouts.app', ['title' => 'Media Edit'])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/tag-input/bootstrap-tagsinput.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    {!! Html::style('assets/plugins/Trumbowyg/ui/trumbowyg.min.css') !!}
@endsection

@section('content')
    <!-- Main container -->
    <main>
        <section class="no-border-bottom">
            <div class="container">
                <form action="{{ route('media.update', $slug) }}" class="form-horizontal" method="POST" enctype="multipart/form-data">

                    <div class="row">

                        @include('errors.list')

                        <div class="col-sm-12 col-md-8">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="card">
                                <div class="card-header">
                                    <h6>Edit Your File</h6>
                                </div>

                                <div class="card-block">
                                    <div class="form-group">
                                        <label for="input-title">Title</label>
                                        <input type="text" name="title" class="form-control input-lg" value="{{ $media->title }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="input-desc">Description</label>
                                        <textarea name="description" class="form-control" id="editor">{{ $media->body }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">

                            <div class="card">
                                <div class="card-header">
                                    <h6>Meta data</h6>
                                </div>

                                <div class="card-block">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">#</div>
                                            <input data-role="tagsinput" id="tags" type="text" value="{{ $tags }}" name="tags" class="form-control bootstrap-tagsinput input-lg">
                                        </div>
                                        <span class="help-block">Separate tags with comma</span>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-folder-o"></i></div>
                                            <select name="category" class="form-control">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ ($media->category_id == $category->id) ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

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

                            @if($media->type == 'video')
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Thumbnail</h6>
                                    </div>

                                    <div class="card-block">
                                        <div class="form-group">
                                            <label for="thumbnail">Set a custom thumbnail:</label>
                                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-header">
                                    <h6>Password</h6>
                                </div>

                                <div class="card-block">
                                    <div class="form-group">
                                        <label for="media-password">Password:</label>
                                        <input type="password" class="form-control" id="media-password" name="media-password">
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block" type="submit">Update</button>
                            <button class="btn btn-danger btn-block delete" type="button">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
    {!! Html::script('assets/js/bootstrap-tagsinput.js') !!}
    {!! Html::script('assets/js/typeahead.js') !!}
    {!! Html::script('assets/plugins/Trumbowyg/trumbowyg.min.js') !!}
    {!! Html::script('assets/plugins/Trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}

    <script>
        $('#tags').tagsinput({
            typeahead: {
                source: [
                    @foreach($common_tags as $tag)
                        "{{ $tag->name }}",
                    @endforeach
                ]
            },
            maxTags: "{{ config("max_tags_per_media") }}",
            trimValue: true
        });

        $('.delete').on('click',function()
        {
            var type = $(this).data('type');
            var id = $(this).data('id');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function()
            {
                var data = {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE",
                }
                var url = "{{ route('media.destroy', $slug) }}";

                $.ajax({
                    url: url,
                    type:"POST",
                    data: data,
                    success: function(data){
                        swal("Deleted!", "Your file has been deleted.", "success", true);
                        window.location.href = '{{ route('user.profile.index', $username) }}';
                    },
                    error:function(){
                        swal("Error!", "System can't delete this media :)", "error");
                    }
                }); //end of ajax
            });

        });

        $('#editor').trumbowyg({
            btns: [
                ['viewHTML'],
                ['formatting'],
                'btnGrp-semantic',
                ['link'],
                ['insertImage'],
                'btnGrp-justify',
                'btnGrp-lists',
                ['horizontalRule'],
                ['removeformat'],
                ['preformatted'],
                ['fullscreen']
            ]
        });
    </script>

@endsection