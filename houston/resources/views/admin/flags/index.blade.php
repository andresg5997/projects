@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Flags
                <small>All Flagged</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Flagged Table</h3>
                </div>
                <div class="box-body">
                    <table class="table table-borderd table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th><i class="fa fa-key"></i></th>
                            <th><i class="fa fa-user"></i></th>
                            <th><i class="fa fa-paper-plane"></i></th>
                            <th><i class="fa fa-file-text-o"></i></th>
                            <th><i class="fa fa-calendar"></i></th>
                            <th><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
@endsection
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function () {

            // Create Table
            var table = $('.table').DataTable({

                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                columns: [
                    {data: 'id', name: 'id', searchable: true},
                    {data: 'user', name: 'user', searchable: false},
                    {data: 'type', name: 'type', searchable: true},
                    {data: 'reason', name: 'reason', searchable: true},
                    {data: 'created_at', name: 'created_at', orderable: true},
                    {data: 'options', name: 'options', orderable: false, searchable: false},
                ],

                ajax: '{{ route('flags.index') }}'

            });

            // Ajax Delete Pagee
            $('.table tbody').on('click', 'td button[type=button]', function (event) {
                var $row = jQuery(this).closest("tr");
                var id = $row.find("button[type=button]").data("id");
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function () {

                    var data = {
                        _token: "{{ csrf_token() }}",
                        type: "delete",
                    }
                    var path = "{{ route('flags.destroy',0) }}";
                    var url = path.replace(0, id);

                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: data,
                        success: function (data) {
                            $row.find("button[type=button]").closest("tr").hide();
                            swal("Deleted!", "The Flag has been deleted.", "success", true);
                            //table.ajax.reload();
                        }, error: function () {
                            swal("Error!", "System Can't Delete This Flag :)", "error");
                        }
                    }); //end of ajax

                });
            });

        });
    </script>
@endsection
