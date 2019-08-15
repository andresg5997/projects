@extends('admin.settings.layouts.advanced')

@section('settings-content')
    <section class="content-header" style="margin-bottom: 25px">
        <h1>
            Log Manager
            <small>Preview, download and delete logs</small>
        </h1>
    </section>

    <section class="content">
        <!-- Default box -->
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Last Modified</th>
                                <th class="text-right">File Size</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $k => $log)
                                <tr>
                                    <th scope="row">{{ $k+1 }}</th>
                                    <td>{{ \Carbon\Carbon::createFromTimeStamp($log['last_modified'])->formatLocalized('%d %B %Y') }}</td>
                                    <td>{{ \Carbon\Carbon::createFromTimeStamp($log['last_modified'])->formatLocalized('%H:%M') }}</td>
                                    <td class="text-right">{{ round((int)$log['file_size']/1048576, 2).' MB' }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-xs btn-default" href="{{ route('advanced.logs.preview', [$log['file_name']]) }}"><i class="fa fa-eye"></i> Preview</a>
                                        <a class="btn btn-xs btn-default" href="{{ route('advanced.logs.download', [$log['file_name']]) }}"><i class="fa fa-cloud-download"></i> Download</a>
                                        <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ route('advanced.logs.delete', [$log['file_name']]) }}"><i class="fa fa-trash-o"></i> Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            PNotify.prototype.options.styling = "bootstrap3";
            PNotify.prototype.options.styling = "fontawesome";

            // capture the delete button
            $("[data-button-type=delete]").click(function (e) {
                e.preventDefault();
                var delete_button = $(this);
                var delete_url = $(this).attr('href');

                swal({
                    title: "Are you sure you want to delete this log file?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!",
                    closeOnConfirm: true
                }, function () {

                    var data = {
                        _token: "{{ csrf_token() }}",
                    };

                    var url = delete_url;

                    $.ajax({
                        url: url,
                        type: "GET",
                        data: data,
                        success: function (data) {
                            new PNotify({
                                title: "Done",
                                text: "The log file was deleted.",
                                type: "success"
                            });
                            delete_button.parentsUntil('tr').parent().remove();
                        }, error: function () {
                            new PNotify({
                                title: "Error",
                                text: "The log file has NOT been deleted.",
                                type: "warning"
                            });
                        }
                    }); //end of ajax

                });
            });
        });

        // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
