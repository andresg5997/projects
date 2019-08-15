@extends('admin.index')

@section('style')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    {!! Html::style('assets/admin/assets/plugins/jquery-ui/jquery.ui.theme.css') !!}
    {!! Html::style('assets/admin/assets/plugins/iCheck/all.css') !!}
@endsection

@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Messages
                <small>Message Read</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><a href="{{ route('messages.index') }}"><i class="fa fa-inbox "></i>
                                    Inbox </a> - Read Mail</h3>
                            <div class="box-tools pull-right">
                                @if($previousMessageID != '')
                                    <a href="{{ route('messages.show', $previousMessageID) }}" class="btn btn-box-tool"
                                       data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                                @endif
                                @if($nextMessageID != '')
                                    <a href="{{ route('messages.show', $nextMessageID) }}" class="btn btn-box-tool"
                                       data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                                @endif
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="mailbox-read-info">
                                <h3>{{ $message->title }}</h3>
                                <h5>From: {{ $message->email }} - {{ $message->name }} <span
                                            class="mailbox-read-time pull-right">{{ $message->created_at }}</span></h5>
                            </div><!-- /.mailbox-read-info -->

                            <div class="mailbox-read-message">
                                {{ $message->message }}
                            </div><!-- /.mailbox-read-message -->
                        </div><!-- /.box-body -->

                        <div class="box-footer">

                            <button id="delete" data-id="{{ $message->id }}" class="btn btn-default"><i
                                        class="fa fa-trash-o"></i> Delete
                            </button>
                        </div><!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
@endsection
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/iCheck/icheck.min.js') !!}
    <script>
        $(document).ready(function () {

            $('#delete').on('click', function () {
                var Ids = $(this).data('id');

                swal({
                    title: "Are you sure ?",
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
                        Ids: Ids
                    }

                    var url = "{{ route('messages.destroy',0) }}";

                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: data,
                        success: function (data) {
                            swal("Deleted!", "The Message has been deleted.", "success", true);
                            setTimeout("location.href='{{ route('messages.index') }}'", 1000);

                        }, error: function () {
                            swal("Error", "System Can't Delete!)", "error");
                        }
                    }); //end of ajax

                });

            });

        });
    </script>
@endsection
