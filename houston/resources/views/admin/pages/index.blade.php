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
                @if (!empty($footer))
                    Footer Pages
                @else
                    Header Pages
                @endif
                <small>All Pages</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('flash_notification.message') !!}
                </div>
            @endif

            <!-- Small boxes (Stat box) -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Pages Table</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr>
                            <th><i class="fa fa-key"></i></th>
                            <th></th>
                            <th><i class="fa fa-link"></i></th>
                            @if (!empty($footer))
                                <th><i class="fa fa-users"></i></th>
                            @endif
                            <th><i class="fa fa-calendar"></i></th>
                            <th><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Slug</th>
                            @if (!empty($footer))
                                <th>Parent?</th>
                            @endif
                            <th>Published</th>
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
                    {data: 'name', name: 'name', searchable: true},
                    {data: 'slug', name: 'slug', searchable: true},
                    @if (!empty($footer))
                        {data: 'parent', name: 'parent', searchable: true},
                    @endif
                    {data: 'created_at', name: 'created_at', orderable: true},
                    {data: 'options', name: 'options', orderable: false, searchable: false},
                ],

                @if (!empty($footer))
                    ajax: '{{ route('footer-pages.index') }}'
                @else
                    ajax: '{{ route('pages.index') }}'
                @endif

        });

            // Ajax Delete Page
            $('.table tbody').on('click', 'td button[type=button]', function (event) {
                var $row = jQuery(this).closest("tr");
                var id = $row.find("button[type=button]").data("id");
                swal({
                    title: "Are you sure?",
                    text: "Note: You will not be able to recover this page.",
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
                    };

                    @if (! empty($footer))
                        var path = "{{ route('footer-pages.destroy', 0) }}";
                    @else
                        var path = "{{ route('pages.destroy', 0) }}";
                    @endif

                    var url = path.replace(0, id);

                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: data,
                        success: function (data) {
                            $row.find("button[type=button]").closest("tr").hide();
                            swal("Deleted!", "The page has been deleted.", "success", true);
                            //table.ajax.reload();
                        }, error: function () {
                            swal("Error!", "System can't delete this page :)", "error");
                        }
                    }); //end of ajax

                });
            });

        });
    </script>
@endsection
