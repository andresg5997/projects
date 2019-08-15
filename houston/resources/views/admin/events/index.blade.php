@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Events
                <small>All Events</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Events Table</h3>
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
                                    <th><i class="fa fa-thumb-tack"></i> Name</th>
                                    <th><i class="fa fa-soccer-ball-o"></i> Sport</th>
                                    <th><i class="fa fa-users"></i> Event Type</th>
                                    <th><i class="fa fa-calendar"></i> Date</th>
                                    <th><i class="fa fa-flag"></i> Team</th>
                                    <th><i class="fa fa-flag"></i> Opponent Team</th>
                                    <th><i class="fa fa-cog"></i> Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>{{ $event->id }}</td>
                                            <td>{{ $event->name }}</td>
                                            <td>{{ $event->sport->name }}</td>
                                            <td>{{ $event->type->name }}</td>
                                            <td>{{ date('d-m-Y H:i', strtotime($event->date)) }}</td>
                                            <td><a href="{{ route('teams.show', $event->teams[0]->id) }}" title="">{{ $event->teams[0]->name }}</a></td>
                                            <td>{{ $event->teams[0]->pivot->team_2 }}</td>
                                            <td>
                                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a>
                                                <button type="button" class="btn btn-sm btn-danger delete" data-id="{{ $event->id }}" data-name="{{ $event->name }}"><i class="fa fa-ban"></i> </button>
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
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    {!! Html::script('js/axios.js') !!}
<script>
    $('.delete').click(function(){
            var $row = jQuery(this).closest("tr");
            var id = $row.find("button[type=button]").data("id");
            var name = $row.find("button[type=button]").data("name");
            swal({
                    title: "Are you sure To Delete <span style='color:#DD6B55'> " + name + " </span>?",
                    text: "You will be not able to recover this event again.",
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

                    var url = '/admin/events/' + id
                    console.log(url)

                    axios.delete(url, data)
                        .then((res) => {
                            if(res.data.deleted){
                                return swal("Deleted!", "The event (" + name + ") has been deleted.", "success", true);
                            }
                            return swal("Error", "System can't delete this event.", "error");
                        })
                        .catch((error) => {
                            swal("Error", "System can't delete this event.", "error");
                        })

                });
        });
</script>
@endsection
