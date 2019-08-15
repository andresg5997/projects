@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Teams
                <small>All Teams</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Teams Table</h3>
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
                            <table class="table table-borderd table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th><i class="fa fa-key"></i> ID</th>
                                    <th><i class="fa fa-flag"></i> Name</th>
                                    <th><i class="fa fa-star"></i> Sport</th>
                                    <th><i class="fa fa-users"></i> Number of players</th>
                                    <th><i class="fa fa-user"></i> Owner</th>
                                    <th><i class="fa fa-calendar"></i> Number of events</th>
                                    <th><i class="fa fa-cog"></i> Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($teams as $team)
                                        <tr>
                                            <td>{{ $team->id }}</td>
                                            <td>{{ $team->name }}</td>
                                            <td>{{ $team->sport->name }}</td>
                                            <td>{{ $team->players->count() }}</td>
                                            <td>
                                                <a href="{{ route('user.profile.index', $team->user->username) }}" title="">
                                                    {{ $team->user->name }}
                                                </a>
                                            </td>
                                            <td>{{ $team->events->count() }}</td>
                                            <td>
                                                <a href="{{ route('teams.show', $team->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> </a>
                                                <a href="{{ route('admin.teams.edit', $team->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a>
                                                <button type="button" data-id="{{ $team->id }}" data-name="{{ $team->name }}" href="#" class="btn btn-sm btn-danger delete"><i class="fa fa-ban"></i> </button>
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
        var id = $row.find("button[type=button]").data("id");
        var name = $row.find("button[type=button]").data("name");
        swal({
                title: "Are you sure To Delete <span style='color:#DD6B55'> " + name + " </span>?",
                text: "You will be not able to recover this team again.",
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

                var url = '/admin/teams/' + id

                axios.delete(url, data)
                    .then((res) => {
                        if(res.data.deleted){
                            return swal("Deleted!", "The team (" + name + ") has been deleted.", "success", true);
                        }
                        return swal("Error", "System can't delete this team.", "error");
                    })
                    .catch((error) => {
                        swal("Error", "System can't delete this team.", "error");
                    })

            });
    });
</script>
@endsection
