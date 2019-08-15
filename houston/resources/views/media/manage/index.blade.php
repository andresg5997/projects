@extends('layouts.app', ['title' => 'File Manager'])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="padding: 0px 25px">
                    <div class="card-header">
                        <h6>{{ trans('manage_media.your_media_header') }}</h6>
                    </div>
                    <div class="card-block">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-key"></i></th>
                                    <th><i class="fa fa-picture-o"></i></th>
                                    <th><i class="fa fa-thumb-tack"></i></th>
                                    <th><i class="fa fa-folder-open"></i></th>
                                    <th><i class="fa fa-eye"></i></th>
                                    <th><i class="fa fa-play"></i></th>
                                    <th><i class="fa fa-heart"></i></th>
                                    <th><i class="fa fa-comments"></i></th>
                                    <th><i class="fa fa-calendar"></i></th>
                                    <th><i class="fa fa-cog"></i></th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! Html::script('assets/admin/assets/plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    <script>
    $(document).ready(function(){

        // Table Filters
        var route = '{{ route('manage.index') }}';

        // Create Table
        var table = $('.table').DataTable({
            url: route,
            processing: true,
            serverSide: true,
            aaSorting : [[0, 'desc']],
            columns: [
                {data: 'id', name: 'id', searchable: true},
                {data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'category', name: 'name', orderable: false, searchable: false},
                {data: 'views', name: 'views', orderable: true, searchable: false},
                {data: 'plays', name: 'plays', orderable: true, searchable: false},
                {data: 'likes', name: 'likes', orderable: false, searchable: false},
                {data: 'comments', name: 'comments', orderable: false, searchable: false},
                {data: 'created_at', name: 'published_at', orderable: true, searchable:false},
                {data: 'options', name: 'options', orderable: false, searchable:false},
            ],
            ajax: route
        });

        // Ajax Delete Post
        $('.table tbody').on( 'click', 'td button[type=button]', function(event) {
            var $row = jQuery(this).closest("tr");
            var id = $row.find("button[type=button]").data("id");
            swal({
                title: "{{ trans('manage_media.are_you_sure') }}",
                text: "{{ trans('manage_media.note') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('manage_media.delete_it') }}!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function(){

                var data = {
                    _token:"{{ csrf_token() }}",
                };
                var path = "{{ route('manage.destroy', 0) }}";

                var url = path = path.replace(0, id);

                $.ajax({
                    url: url,
                    type:"DELETE",
                    data: data,
                    success: function(data) {
                        $row.find("button[type=button]").closest("tr").hide();
                        swal("{{ trans('manage_media.deleted') }}", "{{ trans('manage_media.post_deleted') }}.", "success", true);
                        table.ajax.reload();
                    },
                    error:function(){
                        swal("{{ trans('manage_media.error') }}", "{{ trans('manage_media.cant_delete') }}", "error");
                    }
                }); //end of ajax

            });
        });

        // Ajax Delete Post
        $('.approved').on( 'click', 'td button[type=button]', function(event) {
            var $row = jQuery(this).closest("tr");
            var id = $row.find("button[type=button]").data("id");
            swal({
                title: "{{ trans('manage_media.are_you_sure') }}",
                text: "{{ trans('manage_media.only_database') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('manage_media.approve_it') }}",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            }, function(){

                var data = {
                    _token:"{{ csrf_token() }}",
                };
                var path = "{{ route('medias.approve', 1) }}";

                var url = path = path.replace(0, id);

                $.ajax({
                    url: url,
                    type:"POST",
                    data: data,
                    success:function(data){
                        $row.find("button[type=button]").closest("tr").hide();
                        swal("{{ trans('manage_media.approved') }}", "{{ trans('manage_media.post_approved') }}", "success", true);
                        table.ajax.reload();
                    },error:function(){
                        swal("{{ trans('manage_media.error') }}", "{{ trans('manage_media.cant_approve') }}", "error");
                    }
                }); //end of ajax

            });
        });

    });
    </script>
@endsection
