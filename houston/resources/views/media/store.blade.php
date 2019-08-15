@extends('layouts.app', ['title' => 'Upload'])

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="{{ url('assets/css/jquery.fileupload/jquery.fileupload.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/jquery.fileupload/jquery.fileupload-ui.css') }}">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="{{ url('assets/css/jquery.fileupload/jquery.fileupload-noscript.css') }}"></noscript>
    <noscript><link rel="stylesheet" href="{{ url('assets/css/jquery.fileupload/jquery.fileupload-ui-noscript.css') }}"></noscript>

    <style>
        .files audio,
        .files video,
        .file-name {
            max-width: 250px;
        }
        .margin-top {
            margin-top: 30px;
        }
        .nav-tabs { border-bottom: 2px solid #DDD; }
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
        .nav-tabs > li > a { border: none; color: #666; }
        .nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none; color: #4285F4 !important; background: transparent; }
        .nav-tabs > li > a::after { content: ""; background: #4285F4; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
        .nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
        .tab-nav > li > a::after { background: #21527d none repeat scroll 0% 0%; color: #fff; }
        .tab-pane { padding: 15px 0; }
        .tab-content { margin-top: 20px; }
    </style>
@endsection

@section('content')
    <!-- Main container -->
    <main>

        <!-- Page links -->
        @if (config('remote_uploads') || config('clone_uploads'))
            <div class="page-links">
                <div class="container">
                    <div class="pull-left">
                        <ul id="head-tabs" class="link-list" role="tablist">
                            <li role="presentation"><a href="#classic" class="active" aria-controls="classic" role="tab" data-toggle="tab"><i class="fa fa-cloud-upload"></i> Classic Upload</a></li>
                            @if (config('remote_uploads'))
                                <li role="presentation"><a href="#remote" aria-controls="remote" role="tab" data-toggle="tab"><i class="fa fa-arrows-h"></i> Remote Upload</a></li>
                            @endif
                            @if (config('clone_uploads'))
                                <li role="presentation"><a href="#clone" aria-controls="clone" role="tab" data-toggle="tab"><i class="fa fa-clone"></i> Clone Upload</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <!-- END Page links -->

        <section class="no-border-bottom" style="padding-top: 20px">
            <div class="container">

            @include('components.flash_notification')

                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="classic">

                        <form id="fileupload" action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="direct-upload" value="1">
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="card" style="padding: 0px 25px">
                                        <div class="card-header">
                                            <h6>{{ trans('upload.upload_media_header') }}</h6>
                                        </div>
                                        <div class="card-block">
                                            <div class="fileupload-buttonbar dropzone">
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <span class="btn btn-sm btn-success fileinput-button">
                                                        <i class="fa fa-plus"></i>
                                                        <span>{{ trans('upload.add_files') }}</span>
                                                        <input type="file" name="files[]" multiple>
                                                    </span>
                                                <button type="submit" class="btn btn-sm btn-primary start">
                                                    <i class="fa fa-cloud-upload"></i>
                                                    <span>{{ trans('upload.start_upload') }}</span>
                                                </button>
                                                <button type="reset" class="btn btn-sm btn-warning cancel">
                                                    <i class="fa fa-ban"></i>
                                                    <span>{{ trans('upload.cancel_upload') }}</span>
                                                </button>
                                                <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> Private?</button>
                                                        <input type="checkbox" class="hidden" />
                                                    </span>
                                                <span class="fileupload-process"></span>
                                                <div style="padding-top: 20px">
                                                    <span class="help-block" style="text-align: center;">{{ trans('upload.drag_n_drop') }}</span>
                                                </div>
                                            </div>

                                            <!-- The global progress state -->
                                            <div class="fileupload-progress fade margin-top">
                                                <!-- The global progress bar -->
                                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar total-progress-bar progress-bar-success" style="width:0%;"></div>
                                                </div>
                                                <!-- The extended global progress state -->
                                                {{--<div class="progress-extended">&nbsp;</div>--}}

                                                <div class="progress-bar-holder">
                                                    <div class="prog-time-size">
                                                        <div class="prog-size pull-left"></div>
                                                        <div class="prog-time pull-right"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- The table listing the files available for upload/download -->
                                            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- The template to display files available for upload -->

                        <script id="template-upload" type="text/x-tmpl">
                            {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-upload fade">
                                    <td>
                                        <span class="preview"></span>
                                    </td>
                                    <td class="file-name">
                                        <p class="name">{%=file.name%}</p>
                                        <strong class="error text-danger"></strong>
                                    </td>
                                    <td>
                                        <p class="size">{{ trans('upload.processing') }}</p>
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                        {% if (!i && !o.options.autoUpload) { %}
                                            <button class="btn btn-sm btn-primary start" disabled>
                                                <i class="fa fa-cloud-upload"></i>
                                                <span></span>
                                            </button>
                                        {% } %}
                                        {% if (!i) { %}
                                            <button class="btn btn-sm btn-warning cancel">
                                                <i class="fa fa-ban"></i>
                                                <span></span>
                                            </button>
                                            <span class="button-checkbox">
                                                <button type="button" data-color="white" class="btn btn-sm btn-default btn-private"><i class="state-icon fa fa-square-o"></i></button>
                                                <input name="{%=file.name%}" type="checkbox" class="hidden" />
                                            </span>
                                        {% } %}
                                        </div>
                                    </td>
                                </tr>
                            {% } %}
                        </script>
                        <!-- The template to display files available for download -->
                        <script id="template-download" type="text/x-tmpl">
                            {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-download fade">
                                    <td>
                                        <span class="preview">
                                            {% if (file.thumbnailUrl) { %}
                                                <a href="{%=file.url%}" title="{%=file.name%}"><img src="{%=file.thumbnailUrl%}"></a>
                                            {% } %}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="name">
                                            {% if (file.url) { %}
                                                <a href="{%=file.url%}" title="{%=file.name%}" target="_blank">{%=file.name%}</a>
                                            {% } else { %}
                                                <span>{%=file.name%}</span>
                                            {% } %}
                                        </p>
                                        {% if (file.error) { %}
                                            <div><span class="label label-danger">{{ trans('upload.error') }}</span> {%=file.error%}</div>
                                        {% } %}
                                    </td>
                                    <td>
                                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                            {% if (file.deleteUrl) { %}
                                                <a class="btn btn-sm btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                                    <i class="fa fa-trash"></i>
                                                    <span></span>
                                                </a>
                                                <input type="checkbox" name="delete" value="1" class="toggle hidden">
                                            {% } else { %}
                                                <button class="btn btn-sm btn-warning cancel">
                                                    <i class="fa fa-ban"></i>
                                                    <span></span>
                                                </button>
                                            {% } %}
                                        </div>
                                    </td>
                                </tr>
                            {% } %}
                        </script>

                    </div>

                    @if (config('remote_uploads'))
                        <div role="tabpanel" class="tab-pane" id="remote">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="card" style="padding: 0px 25px">
                                        <div class="card-header">
                                            <h6>{{ trans('upload.remote_upload_header') }}</h6>
                                        </div>

                                        <div class="card-block tabs">
                                            <!-- Nav tabs -->
                                            <ul id="tabs" class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#classic-remote" aria-controls="classic-remote" role="tab" data-toggle="tab">{{ trans('upload.simple') }}</a></li>
                                                @if (command_exist('youtube-dl --help') && isEnabled('exec'))
                                                    <li role="presentation"><a href="#media-hosts" aria-controls="media-hosts" role="tab" data-toggle="tab">{{ trans('upload.media_hosts') }}</a></li>
                                                @endif
                                                <li role="presentation"><a href="#gdrive" aria-controls="gdrive" role="tab" data-toggle="tab">Google Drive</a></li>
                                                <li role="presentation"><a href="#dropbox" aria-controls="dropbox" role="tab" data-toggle="tab">Dropbox</a></li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content">

                                                <div role="tabpanel" class="tab-pane active" id="classic-remote">
                                                    <button class="btn btn-sm btn-primary remote-upload-start">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{{ trans('upload.transfer_files') }}</span>
                                                    </button>
                                                    <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> {{ trans('upload.private') }}</button>
                                                        <input type="checkbox" class="hidden private-remote" />
                                                    </span>
                                                    <textarea rows="5" name="url-list" id="url-list-classic-remote" class="margin-top form-control" placeholder="https://example-site.com/file.zip\nhttps://example-site.com/movie.mp4\nhttps://example-site.com/audio.mp3"></textarea>
                                                    <p>{{ trans('upload.paste_up_to') }}</p>
                                                    <p class="small">{{ trans('upload.download_directly') }}</p>
                                                </div>

                                                <div role="tabpanel" class="tab-pane" id="media-hosts">
                                                    <button class="btn btn-sm btn-primary remote-media-hosts-upload-start">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{{ trans('upload.transfer_files') }}</span>
                                                    </button>
                                                    <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> Private?</button>
                                                        <input type="checkbox" class="hidden private-remote" />
                                                    </span>
                                                    <textarea rows="5" name="url-list" id="url-list-media-hosts" class="margin-top form-control" placeholder="https://www.youtube.com/watch?v=3cxixDgHUYw\nhttps://openload.co/f/tr6gjooZMj0/big_buck_bunny_240p_5mb.3gp.mp4\nhttps://vimeo.com/103843805\nAnd a lot more ..."></textarea>
                                                    <p>{{ trans('upload.paste_up_to') }}</p>
                                                    <p class="small">{{ trans('upload.list_of_supp_hosts') }} <a href="{{ route('supported-media-hosts') }}">{{ trans('upload.here') }}</a>.</p>
                                                </div>

                                                <div role="tabpanel" class="tab-pane" id="dropbox">
                                                    <button class="btn btn-sm btn-primary remote-dropbox-upload-start">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{{ trans('upload.transfer_files') }}</span>
                                                    </button>
                                                    <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> {{ trans('upload.private') }}</button>
                                                        <input type="checkbox" class="hidden private-remote" />
                                                    </span>
                                                    <textarea rows="5" name="url-list" id="url-list-dropbox" class="margin-top form-control" placeholder="https://www.dropbox.com/sh/qx4l4phl6l19g5t/AAATj91w7i78etrVTtzXtnta?dl=0\nhttps://www.dropbox.com/s/zu6o8ouprey28wk/test.zip?dl=1"></textarea>
                                                    <p>{{ trans('upload.paste_up_to') }}</p>
                                                    <p class="small">{{ trans('upload.share_link') }}</p>
                                                </div>

                                                <div role="tabpanel" class="tab-pane" id="gdrive">
                                                    <button class="btn btn-sm btn-primary remote-gdrive-upload-start">
                                                        <i class="fa fa-cloud-upload"></i>
                                                        <span>{{ trans('upload.transfer_files') }}</span>
                                                    </button>
                                                    <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> {{ trans('upload.private') }}</button>
                                                        <input type="checkbox" class="hidden private-remote" />
                                                    </span>
                                                    <textarea rows="5" name="url-list" id="url-list-gdrive" class="margin-top form-control" placeholder="0BygTO8aWqs9YWnJqSXRKTE9nUlE\nhttps://drive.google.com/open?id=0BygTO8aWqs9YWnJqSXRKTE9nUlE\nhttps://drive.google.com/file/d/0BygTO8aWqs9YWnJqSXRKTE9nUlE/view"></textarea>
                                                    <p>{{ trans('upload.paste_id_or_link') }}</p>
                                                    <p class="small">{{ trans('upload.allowed_to_share') }}</p>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                    @if(config('clone_uploads'))
                        <div role="tabpanel" class="tab-pane" id="clone">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="card" style="padding: 0px 25px">
                                        <div class="card-header">
                                            <h6>{{ trans('upload.clone_upload_header') }}</h6>
                                        </div>

                                        <div class="card-block">

                                            <!-- Tab panes -->
                                            <div class="tab-content">

                                                <div role="tabpanel" class="tab-pane active" id="clone">
                                                    <button class="btn btn-sm btn-primary clone-upload-start">
                                                        <i class="fa fa-clone"></i>
                                                        <span>{{ trans('upload.copy_files') }}</span>
                                                    </button>
                                                    <span class="button-checkbox">
                                                        <button id="select-all" type="button" class="btn btn-sm btn-default" data-color="white"><i class="state-icon fa fa-square-o"></i> {{ trans('upload.private') }}</button>
                                                        <input type="checkbox" class="hidden private-clone" />
                                                    </span>
                                                    <textarea rows="5" name="url-list" id="url-list-clone" class="margin-top form-control" placeholder="https://clooud.tv/media/big-buck-bunny-240p-mp4-391\nhttps://clooud.tv/media/beauty-and-the-beast-trailer-139"></textarea>
                                                    <p>{{ trans('upload.paste_up_to') }}</p>
                                                    <p class="small">{{ trans('upload.clone_files_desc') }}</p>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                </div>

            </div>
        </section>
    </main>
    <!-- END Main container -->
@endsection

@section('scripts')
    <script src="{{ url('assets/js/jquery.fileupload/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/tmpl.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/load-image.all.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/canvas-to-blob.min.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.iframe-transport.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-process.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-image.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-audio.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-video.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-validate.js') }}"></script>
    <script src="{{ url('assets/js/jquery.fileupload/jquery.fileupload-ui.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
        $(function () {
            'use strict';

            function getReadableFileSizeString(fileSizeInBytes) {
                var i = -1;
                var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
                do {
                    fileSizeInBytes = fileSizeInBytes / 1000;
                    i++;
                } while (fileSizeInBytes > 1000);
                return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
            }

            function secondsToHms(d) {
                d = Number(d);
                var h = Math.floor(d / 3600);
                var m = Math.floor(d % 3600 / 60);
                var s = Math.floor(d % 3600 % 60);
                // return ((h > 0 ? h + "h:" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + "min:" : "0:") + (s < 10 ? "0" : "") + s+ "s");
                return ((h > 0 ? "About " + h + " hour and " + m + " minutes remaining." : (m > 0 ? "About " + m + " minutes remaining." : "Less than 1 minute remaining")));
            }

            @if(Auth::check())
                var max = {{ config('max_file_upload_size_user') * 1000000 }};
                var amount = {{ config('max_amount_of_concurrent_uploads_user') }};
            @else
                var max = {{ config('max_file_upload_size_guest') * 1000000 }};
                var amount = {{ config('max_amount_of_concurrent_uploads_guest') }};
            @endif

            // Initialize the jQuery File Upload widget:
            $('#fileupload').fileupload({
                url: '{{route('media.upload')}}',

                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
                maxNumberOfFiles: amount,
                maxFileSize: max,
                maxChunkSize: 50000000, // 50 MB

                progressall: function (e, data) {
                    $(".prog-size").html("<p class='lead'>"+getReadableFileSizeString(data.loaded)+" / "+getReadableFileSizeString(data.total)+"</p>");
                    $(".prog-time").html("<p class='lead'>"+secondsToHms((data.total-data.loaded)/(data.bitrate/8))+ " @ " + getReadableFileSizeString(data.bitrate/8)+"/s"+"</p>");
                    $(".total-progress-bar").css({width:(100*data.loaded/data.total).toFixed(0)+"%"});
                },

                success: function(data) {
                    // console.log('success '+data);
                }
            })
                .bind('fileuploadchunksend', function (e, data) {
                    // console.log("fileuploadchunksend");
                })
                .bind('fileuploadchunkdone', function (e, data) {
                    // console.log("fileuploadchunkdone");
                })
                .bind('fileuploadchunkfail', function (e, data) {
                    console.log("fileuploadchunkfail")
                })
                .bind('fileuploaddone', function (e, data) {
                    for(var i=0; i<data.result.length; i++)
                        $(this).fileupload('option', 'done')
                            .call(this, $.Event('done'), {result: data.result[i]});
                })
                // load existing files
                .addClass('fileupload-processing');
            $.ajax({
                // Uncomment the following to send cross-domain cookies:
                //xhrFields: {withCredentials: true},
                url: $('#fileupload').fileupload('option', 'url'),
                dataType: 'json',
                context: $('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            })
                .done(function (result) {
                    $(this).fileupload('option', 'done')
                        .call(this, $.Event('done'), {result: result});
            });

            // Upload server status check for browsers with CORS support:
            if ($.support.cors) {
                $.ajax({
                    url: '{{route('media.upload')}}',
                    type: 'HEAD'
                }).fail(function () {
                    $('<div class="alert alert-danger"/>')
                        .text('Upload server currently unavailable - ' + new Date())
                        .appendTo('#fileupload');
                });
            }
        });

        $('#tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#head-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
            $('#head-tabs a').removeClass('active');
            $(this).addClass('active');
        });

        // create option to Make toggleable button for private
        function toggleElements($btn, $checkbox, state) {
            $checkbox.prop('checked', state);
            $btn.toggleClass('btn-default btn-white');
            $btn.find('.state-icon').toggleClass('fa-square-o fa-check-square-o');
        }

        function toggleOne(event) {
            var $btn = $(this);
            var $icon = $btn.find('.state-icon');
            var $checkbox = $btn.siblings('input');
            var isChecked = $checkbox.prop('checked');

            //always untoggle select-all if any of the other button was pressed
            var $selectAllBtn = $("#select-all");
            var $selectAllCheckBox = $selectAllBtn.siblings("input");
            if (!$(event.target).is($selectAllBtn)) {
                toggleElements($selectAllBtn, $selectAllCheckBox, false);
                $selectAllBtn.removeClass('btn-white').addClass('btn-default');
                $selectAllBtn.find('.state-icon').removeClass('fa-check-square-o').addClass('fa-square-o');
            }

            // check and toggle individual check/button
            if ($selectAllCheckBox.prop('checked')) { // if select all is checked
                if (!isChecked) { // only toggle if not already checked
                    toggleElements($btn, $checkbox, !isChecked);
                }
            } else { // toggle as normal
                toggleElements($btn, $checkbox, !isChecked);
                var private_name = $checkbox.attr("name").replace(/[^\w]/g,'');
                $checkbox.prop('name', private_name);

            }
        }

        function selectAll(event) {
            var $selectAllBtn = $(this);
            var $icon = $selectAllBtn.find('.state-icon');
            var $selectAllCheckBox = $selectAllBtn.siblings('input');
            var isChecked = $selectAllCheckBox.prop('checked');

            // handle select-all here
            toggleElements($selectAllBtn, $selectAllCheckBox, !isChecked);

            // Iterate each checkbox except select-all
            $(':checkbox').each(function(idx, element) {
                var $btn = $(element).prev();
                var $checkbox = $btn.siblings('input');
                if (!$btn.is("#select-all")) { // except select-all
                    toggleOne.call($btn, event);
                    var private_name = $checkbox.attr("name").replace(/[^\w]/g,'');
                    $checkbox.prop('name', private_name);
                }
            });
        }

        $(document).on('click', '.btn-private', toggleOne);
        $(document).on('click', '#select-all', selectAll);

        // Remote Upload and Cloning
        $('.remote-upload-start').on('click',function() {
            swalRemoteUpload("classic-remote", "remote");
        });

        $('.remote-media-hosts-upload-start').on('click',function() {
            swalRemoteUpload("media-hosts", "remote");
        });

        $('.remote-gdrive-upload-start').on('click',function() {
            swalRemoteUpload("gdrive", "remote");
        });

        $('.remote-dropbox-upload-start').on('click',function() {
            swalRemoteUpload("dropbox", "remote");
        });

        $('.clone-upload-start').on('click',function() {
            swalRemoteUpload("clone", "clone");
        });

        function swalRemoteUpload(type, remoteOrClone) {
            // get array of URLs/IDs
            var urls = getUrls($("#url-list-" + type).val());

            swal({
                title: "Are those the links?",
                text: "These files will automatically be downloaded and once they are downloaded, you will be notified via email.",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#159eee",
                confirmButtonText: "Yes, add to my account!",
                closeOnConfirm: false
            },
            function() {
                const data = {
                    _token: "{{ csrf_token() }}",
                    _method: "POST",
                    remote_upload: 1,
                    type: type,
                    private_test: $(".private-" + remoteOrClone).prop("checked"),
                    url_list: urls,
                };
                const url = "{{ route('media.upload') }}";
                $.ajax({
                    url: url,
                    type:"POST",
                    data: data,
                    beforeSend: function(){
                        swal({
                            title: "Downloading!",
                            text: "Your request has been made and we are downloading. You will be notified via email once they are added. (You can close this window if you want) ",
                            imageUrl: "{{ url('assets/images/loading.gif') }}",
                            showCancelButton: true
                        }, function() {
                            // Redirect the user
                            window.location.href = "{{ route('media.upload') }}";
                        });
                    },
                    success:function(){
                        swal("Success!", "It has been added to your account successfully.", "success");
                    },
                    error:function(){
                        swal("Error!", "There is an issue processing your remote upload :( Please contact us.", "error");
                    }
                }); //end of ajax
            });
        }

        // clean empty array elements, in case there are line breaks in the url text area
        Array.prototype.clean = function(deleteValue) {
            for (var i = 0; i < this.length; i++) {
                if (this[i] == deleteValue) {
                    this.splice(i, 1);
                    i--;
                }
            }
            return this;
        };

        function getUrls(url_list) {
            var urls = url_list.split("\n");

            // strip empty elements out
            urls.clean("");
            // only take first 5 URLs

            return urls;
        }

        // make multi line placeholders
        var textAreas = document.getElementsByTagName('textarea');

        Array.prototype.forEach.call(textAreas, function(elem) {
            elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
        });
    </script>
@endsection