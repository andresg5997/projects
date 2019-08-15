@extends('admin.settings.layouts.advanced')

@section('settings-content')
    <section class="content-header" style="margin-bottom: 25px">
        <h1>
            Backup Manager
            <small>Keep your application files and database safe</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Default box -->
                <div class="box">
                    <div class="box-body">
                        <button id="create-new-backup-button" href="{{ route('advanced.backups.create') }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> Create a new backup</span></button>
                        <br>
                        <h3>Existing backups:</h3>
                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Date</th>
                                <th class="text-right">File Size</th>
                                <th class="text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($backups as $k => $b)
                                <tr>
                                    <th scope="row">{{ $k+1 }}</th>
                                    <td>{{ $b['disk'] }}</td>
                                    <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}</td>
                                    <td class="text-right">{{ round((int)$b['file_size']/1048576, 2).' MB' }}</td>
                                    <td class="text-right">
                                        @if ($b['download'])
                                            <a class="btn btn-xs btn-default" href="{{ route('advanced.backups.download') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="fa fa-cloud-download"></i> Download</a>
                                        @endif
                                        <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ route('advanced.backups.delete') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="fa fa-trash-o"></i> Delete</a>
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
    {!! Html::script('/assets/admin/assets/plugins/ladda/spin.js') !!}
    {!! Html::script('/assets/admin/assets/plugins/ladda/ladda.js') !!}

    <script>
        jQuery(document).ready(function($) {

            PNotify.prototype.options.styling = "bootstrap3";
            PNotify.prototype.options.styling = "fontawesome";

            // capture the Create new backup button
            $("#create-new-backup-button").click(function(e) {
                e.preventDefault();
                var create_backup_url = "{{ route('advanced.backups.create') }}";
                // Create a new instance of ladda for the specified button
                var l = Ladda.create( document.querySelector( '#create-new-backup-button' ) );

                // Start loading
                l.start();

                // Will display a progress bar for 10% of the button width
                l.setProgress( 0.3 );

                setTimeout(function(){ l.setProgress( 0.6 ); }, 2000);

                var data = {
                    _token: "{{ csrf_token() }}",
                };

                // do the backup through ajax
                $.ajax({
                    url: create_backup_url,
                    type: 'PUT',
                    data: data,
                    success: function(result) {
                        l.setProgress( 0.9 );
                        // Show an alert with the result
                        if (result.indexOf('failed') >= 0) {
                            new PNotify({
                                title: "Unknown error",
                                text: "Your backup may NOT have been created. Please check log files for details.",
                                type: "warning"
                            });
                        }
                        else
                        {
                            new PNotify({
                                title: "Backup completed",
                                text: "Reloading the page in 3 seconds.",
                                type: "success"
                            });
                        }

                        // Stop loading
                        l.setProgress( 1 );
                        l.stop();

                        // refresh the page to show the new file
                        setTimeout(function(){ location.reload(); }, 3000);
                    },
                    error: function(result) {
                        l.setProgress( 0.9 );
                        // Show an alert with the result
                        new PNotify({
                            title: "Backup error",
                            text: "The backup file could NOT be created.",
                            type: "warning"
                        });
                        // Stop loading
                        l.stop();
                    }
                });
            });

            // capture the delete button
            $("[data-button-type=delete]").click(function(e) {
                e.preventDefault();
                var delete_button = $(this);
                var delete_url = $(this).attr('href');

                swal({
                    title: "Are you sure you want to delete this backup file?",
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
                                text: "The backup file was deleted.",
                                type: "success"
                            });
                            delete_button.parentsUntil('tr').parent().remove();
                        }, error: function () {
                            new PNotify({
                                title: "Error",
                                text: "The backup file has NOT been deleted.",
                                type: "warning"
                            });
                        }
                    }); //end of ajax

                });
            });

        });
    </script>
@endsection
