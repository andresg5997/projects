
    {{-- Upload scripts --}}

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
                    url: '{{route('team.media.upload')}}',

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
                        url: '{{route('team.media.upload')}}',
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
                    const url = "{{ route('team.media.upload') }}";
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
                                window.location.href = "{{ route('team.media.upload') }}";
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