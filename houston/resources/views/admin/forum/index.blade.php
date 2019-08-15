@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Forum Categories
                <small>All Categories</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Categories Table</h3>
                        </div>
                        <div class="box-body">
                            @if (session()->has('flash_notification.message'))
                                <div class="alert alert-{{ session('flash_notification.level') }}">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    {!! session('flash_notification.message') !!}
                                </div>
                            @endif
                            <table class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th><i class="fa fa-key"></i></th>
                                    <th><i class="fa fa-glass"></i></th>
                                    <th><i class="fa fa-thumb-tack"></i></th>
                                    <th><i class="fa fa-link"></i></th>
                                    <th><i class="fa fa-calendar"></i></th>
                                    <th><i class="fa fa-cog"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Color</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Created at</th>
                                    <th>Options</th>
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
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function () {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'color', name: 'color', orderable: false, searchable: false},
                    {data: 'name', name: 'name', orderable: true, searchable: true},
                    {data: 'slug', name: 'slug'},
                    {data: 'created_at', name: 'created_at', orderable: true},
                    {data: 'options', name: 'options', orderable: false, searchable: false},
                ],

                ajax: '{{ route('forum.index') }}'
            });

            // Ajax Delete Post
            $('.table tbody').on('click', 'td button[type=button]', function (event) {
                event.preventDefault();
                var $row = jQuery(this).closest("tr");
                var id = $row.find("button[type=button]").data("id");
                var name = $row.find("button[type=button]").data("name");
                var media = $row.find("button[type=button]").data("media");
                swal({
                    title: "Are you sure To Delete <span style='color:#DD6B55'> " + name + " </span>?",
                    text: "This Category: <span style='color:#DD6B55'> " + name + " has (" + media + ") Media </span><br/><br/>Note: You will be not able to recover this Category and Related Media Again.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    html: true
                }, function () {

                    var data = {
                        _token: "{{ csrf_token() }}",
                    }

                    var path = "{{ route('forum.destroy',0) }}";
                    var url = path.replace(0, id);

                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: data,
                        success: function (data) {
                            swal("Deleted!", "The Category (" + name + ") has been deleted.", "success", true);
                            table.ajax.reload();
                        }, error: function () {
                            swal("Error", "System Can't Delete This Category! :)", "error");
                        }
                    }); //end of ajax

                });
            });
        });
    </script>
@endsection
