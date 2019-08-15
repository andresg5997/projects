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
            <h1>Media
                <small>All Media</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Media Table</h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th><i class="fa fa-key"></i></th>
                                    <th><i class="fa fa-user"></i></th>
                                    <th><i class="fa fa-picture-o"></i></th>
                                    <th><i class="fa fa-thumb-tack"></i></th>
                                    <th><i class="fa fa-folder-open"></i></th>
                                    <th><i class="fa fa-comments"></i></th>
                                    <th><i class="fa fa-calendar"></i></th>
                                    <th><i class="fa fa-cog"></i></th>
                                </tr>
                                </thead>

                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Thumb</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Comments</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
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

            // Table Filters
            var route = '{{ route('medias.index') }}';

            // Create Table
            var table = $('.table').DataTable({
                url: route,
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                columns: [
                    {data: 'id', name: 'id', searchable: true},
                    {data: 'user', name: 'user', orderable: false, searchable: false},
                    {data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                    {data: 'title', name: 'title'},
                    {data: 'category', name: 'name', orderable: false, searchable: false},
                    {data: 'comments', name: 'comments', orderable: false, searchable: false},
                    {data: 'created_at', name: 'published_at', orderable: true, searchable: false},
                    {data: 'options', name: 'options', orderable: false, searchable: false},
                ],
                ajax: route
            });

            // Ajax Approve/Delete Post
            $('.table tbody').on('click', 'td button[type=button]', function (event) {
                var id = $(this).attr('data-id');
                var type = $(this).attr('data-type');
                var text = "";
                var confirmButtonText = "";
                var path = "";
                var url = "";
                var confirmButtonColor = "";
                var action = "";
                var message = "";
                var error_message = "";

                if(type == "delete") {
                    text = "Note: You will be able to recover this Post with Related Comments Again From Trash.";
                    confirmButtonText = "Yes, delete it!";
                    path = "{{ route('medias.destroy', 0) }}";
                    url = path = path.replace(0, id);
                    confirmButtonColor = "#DD6B55";
                    type = "DELETE";
                    action = "Deleted";
                    message = "This post has been deleted.";
                    error_message = "System can't delete this post :(";
                } else if(type == "approve") {
                    text = "Note: It will be published on your home page.";
                    confirmButtonText = "Yes, approve it!";
                    path = "{{ route('media.approve', [0, 1]) }}";
                    url = path = path.replace(0, id);
                    confirmButtonColor = "#309942";
                    type = "GET";
                    action = "Approved";
                    message = "This post has been approved.";
                    error_message = "System can't approve this post. :(";
                }

                swal({
                    title: "Are you sure?",
                    text: text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    confirmButtonText: confirmButtonText,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                }, function () {

                    var data = {
                        _token: "{{ csrf_token() }}",
                    };

                    $.ajax({
                        url: url,
                        type: type,
                        data: data,
                        success: function (data) {
                            if (type == "DELETE") {
                                $row.find("button[type=button]").closest("tr").hide();
                            }
                            swal(action+"!", message, "success", true);
                            table.ajax.reload();
                        }, error: function () {
                            swal("Error!", error_message, "error");
                        }
                    }); //end of ajax

                });
            });

        });
    </script>
@endsection
