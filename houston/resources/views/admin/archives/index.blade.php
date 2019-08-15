@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Archives
                <small>All Archives</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Archives Table</h3>
                        </div>
                        <div class="box-body">
                            @if (session()->has('flash_notification.message'))
                                <div class="alert alert-{{ session('flash_notification.level') }}">
                                    <button archive="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    {!! session('flash_notification.message') !!}
                                </div>
                            @endif
                            <table class="table table-borderd table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th><i class="fa fa-key"></i> ID</th>
                                    <th><i class="fa fa-flag"></i> Name</th>
                                    <th><i class="fa fa-user"></i> Uploaded by</th>
                                    <th><i class="fa fa-clock-o"></i> Date</th>
                                    <th><i class="fa fa-cog"></i> Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($archives as $archive)
                                        <tr>
                                            <td>{{ $archive->id }}</td>
                                            <td>{{ $archive->name }}</td>
                                            <td>
                                                <a href="{{ route('user.profile.index', $archive->user->username) }}" title="">
                                                    {{ $archive->user->name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ date('m-d-Y h:i a', strtotime($archive->created_at)) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.archives.edit', $archive->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a>
                                                <button archive="button" data-id="{{ $archive->id }}" data-name="{{ $archive->name }}" href="#" class="btn btn-sm btn-danger delete"><i class="fa fa-ban"></i> </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
@endsection
@section('javascript')
{!! Html::script('js/axios.js') !!}
{!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
<script>
    $('.delete').click(function(){
        var $row = jQuery(this).closest("tr");
        var id = $row.find("button[archive=button]").data("id");
        var name = $row.find("button[archive=button]").data("name");
        swal({
                title: "Are you sure To Delete <span style='color:#DD6B55'> " + name + " </span>?",
                text: "You will be not able to recover this archive again.",
                archive: "warning",
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

                var url = '/admin/archives/' + id

                axios.delete(url, data)
                    .then((res) => {
                        if(res.data.deleted){
                            setTimeout(location.reload.bind(location), 2000)
                            return swal("Deleted!", "The archive (" + name + ") has been deleted.", "success", true);
                        }
                        return swal("Error", "System can't delete this archive.", "error");
                    })
                    .catch((error) => {
                        swal("Error", "System can't delete this archive.", "error");
                    })

            });
    });
</script>
@endsection
