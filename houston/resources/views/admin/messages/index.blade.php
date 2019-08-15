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
                <small>Messages Inbox</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-inbox"></i> Messages Inbox</h3>
                        </div>
                        <div class="box-body">
                            <section class="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body no-padding">

                                            <div class="mailbox-controls">
                                                <!-- Check all button -->
                                                <button class="btn btn-default btn-sm checkbox-toggle"><i
                                                            class="fa fa-square-o"></i></button>
                                                <div class="btn-group">
                                                    <button id="delete" class="btn btn-default btn-sm"><i
                                                                class="fa fa-trash-o"></i></button>
                                                </div><!-- /.btn-group -->
                                                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>
                                                </button>
                                                <div class="pull-right">
                                                    {{ $messages->currentPage() }}-{{$messages->count()}}
                                                    /{{ $messages->total() }}
                                                    <div class="btn-group">
                                                        <button class="btn btn-default btn-sm"><i
                                                                    class="fa fa-chevron-left"></i></button>
                                                        <button class="btn btn-default btn-sm"><i
                                                                    class="fa fa-chevron-right"></i></button>
                                                    </div><!-- /.btn-group -->
                                                </div><!-- /.pull-right -->
                                            </div>
                                            <div class="table-responsive mailbox-messages">
                                                <table class="table table-hover ">
                                                    <tbody>
                                                    @foreach($messages as $message)
                                                        @if($message->read == false)
                                                            <tr style="background:#ccc;">
                                                        @else
                                                            <tr>
                                                                @endif
                                                                <td>
                                                                    <div class="icheckbox_flat-blue"
                                                                         aria-checked="false" aria-disabled="false"
                                                                         style="position: relative;">
                                                                        <input name="check[]"
                                                                               data-id="{{ $message->id }}"
                                                                               data-name="{{$message->name}}"
                                                                               type="checkbox"
                                                                               style="position: absolute; opacity: 0;"/>
                                                                        <ins class="iCheck-helper"
                                                                             style="top: 0%; left: 0%; display: block;  margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                                                                    </div>
                                                                </td>
                                                                <td class="mailbox-name"><a
                                                                            href="{{ route('messages.show',$message->id) }}">{{ $message->name }}</a>
                                                                </td>
                                                                <td class="mailbox-subject">
                                                                    <a href="{{ route('messages.show',$message->id) }}">
                                                                        <b>{{ str_limit($message->title,40) }}</b>
                                                                    </a> - {{ str_limit($message->message, 40) }}
                                                                </td>
                                                                <td class="mailbox-attachment"></td>
                                                                <td class="mailbox-date">{{ $message->created_at->diffForHumans() }}</td>
                                                            </tr>
                                                            @endforeach
                                                    </tbody>
                                                </table><!-- /.table -->
                                            </div><!-- /.mail-box-messages -->
                                            <div class="box-footer no-padding">
                                                <div class="mailbox-controls">
                                                    <!-- Check all button -->
                                                    <button class="btn btn-default btn-sm checkbox-toggle"><i
                                                                class="fa fa-square-o"></i></button>
                                                    <div class="btn-group">
                                                        <button id="delete" class="btn btn-default btn-sm"><i
                                                                    class="fa fa-trash-o"></i></button>
                                                    </div><!-- /.btn-group -->
                                                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i>
                                                    </button>
                                                    <div class="pull-right">
                                                        {{ $messages->currentPage() }}-{{$messages->count()}}
                                                        /{{ $messages->total() }}
                                                        <div class="btn-group pagination">
                                                            <a href="{{ $messages->previousPageUrl() }}"
                                                               class="btn btn-default btn-sm "><i
                                                                        class="fa fa-chevron-left"></i></a>
                                                            <a href="{{ $messages->nextPageUrl() }}"
                                                               class="btn btn-default btn-sm "><i
                                                                        class="fa fa-chevron-right"></i></a>
                                                        </div><!-- /.btn-group -->
                                                    </div><!-- /.pull-right -->
                                                </div>
                                            </div>
                                        </div><!-- /. box -->
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </section>
                        </div>
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
                var Ids = [];
                var name;

                $("input:checkbox[type=checkbox]:checked").each(function () {
                    Ids.push($(this).data('id'));
                    name = $(this).data('name');
                });

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
                            swal("Deleted!", "The Message/s has been deleted.", "success", true);
                            setTimeout("location.href='{{ route('messages.index') }}'", 1000);
                        }, error: function () {
                            swal("Error", "System Can't Delete!)", "error");
                        }
                    }); //end of ajax

                });

            });

            $(function () {

                //Enable iCheck plugin for checkboxes
                //iCheck for checkbox and radio inputs
                $('.mailbox-messages input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                });

                //Enable check and uncheck all functionality
                $(".checkbox-toggle").click(function () {
                    var clicks = $(this).data('clicks');
                    if (clicks) {
                        //Uncheck all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                    } else {
                        //Check all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("check");
                        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                    }
                    $(this).data("clicks", !clicks);

                });
            });


        });
    </script>
@endsection
