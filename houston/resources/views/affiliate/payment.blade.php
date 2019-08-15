@extends('layouts.app', ['title' => 'Affiliate Payments'])

@section('styles')
    <link href="{{url('assets/admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet">
@endsection

@section('content')
    <!-- Main container -->
    <main>

        @include('components.page_links', ['view' => 'affiliate'])

        <section class="no-border-bottom section-sm">
            <div class="container">

                @include('components.flash_notification')

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Request a Payment</h6>
                            </div>

                            <div class="card-block">
                                <div class="alert alert-info" role="alert">
                                    <p class="small">Your current account balance is:</p>
                                    <p class="lead"><strong>{{ $current_account_balance }}</strong></p>
                                    @if($payout_check == 0)
                                        @php
                                            function floorp($val, $precision)
                                             {
                                                $mult = pow(10, $precision);
                                                return floor($val * $mult) / $mult;
                                            }
                                        @endphp
                                        <p class="small">You need to earn another ${{floorp($payout_minimum - $current_account_balance, 2)}} in order to request a payout.</p>
                                    @endif
                                </div>
                                @if($payout_check == 1)
                                    <div class="alert alert-success" role="alert">
                                        <p class="lead">You qualify for a {{ $current_account_balance }} payout! Request it now.</p>
                                    </div>
                                    <center>
                                        <a class="btn btn-round btn-success payme" href="#">Pay Me</a>
                                    </center>
                                @endif
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6>Payment Information</h6>
                            </div>

                            <div class="card-block">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-paypal"></i></div>
                                        <input id="paypal" type="email" class="form-control" placeholder="{{ isset($paypal) ? $paypal : "Your Paypal Email..." }}" size="35" name="paypal">
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                                        <input id="payza" type="email" class="form-control" placeholder="{{ isset($payza) ? $payza : "Your Payza Email..." }}" size="35" name="payza">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm email">Update</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Payout History</h6>
                            </div>

                            <div class="card-block">
                                <table class="table-bordered table-striped table-condensed table">
                                    @if(! empty($history_payouts))
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($history_payouts as $history_payout)
                                            @if(! empty($history_payout['paid_date']))
                                                <tr>
                                                    <td>{{ $history_payout['paid_date'] }}</td>
                                                    <td class="txt-green"><strong>${{ number_format($history_payout['requested_amount'], 2) }}</strong></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>Pending</td>
                                                    <td class="txt-green"><strong>${{ number_format($history_payout['requested_amount'], 2) }}</strong></strong></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>{{ $history_payout['created_at'] }}</td>
                                                <td class="txt-red"><strong>${{ number_format($history_payout['requested_amount'], 2) }}</strong></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    @else
                                        <p>You currently have no payout history.</p>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    <script>
        $('.payme').on('click',function()
        {
            swal({
                title: "Are you sure?",
                text: "Your payout request will get worked on and it cannot be guaranteed to be undone. Contact us for further information.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, pay me!",
                closeOnConfirm: false
            },
            function()
            {
                const data = {
                    _token: "{{ csrf_token() }}",
                    _method: "POST",
                }
                const url = "{{ route('affiliate.pay_me') }}";

                $.ajax({
                    url: url,
                    type:"POST",
                    data: data,
                    success: function(){
                        swal("Getting Paid!", "Your request has been made and we are working on it.", "success", true);
                        window.location.href = '{{ route('affiliate.payment') }}';
                    },
                    error:function(){
                        swal("Error!", "There is an issue processing the payout request :( Please contact us.", "error");
                    }
                }); //end of ajax
            });
        });

        $('.email').on('click',function()
        {
            const paypal = $("#paypal").val();
            const payza = $("#payza").val();

            swal({
                    title: "Is this the right address?",
                    text: "Please double check for spelling errors.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, change it!",
                    closeOnConfirm: false
                },
                function()
                {
                    const data = {
                        _token: "{{ csrf_token() }}",
                        _method: "PATCH",
                        paypal: paypal,
                        payza: payza,
                    }
                    const url = "{{ route('affiliate.update.email') }}";

                    $.ajax({
                        url: url,
                        type:"POST",
                        data: data,
                        success: function(){
                            swal("Changed it!", "Your payment email address has been changed.", "success", true);
                            window.location.href = '{{ route('affiliate.payment') }}';
                        },
                        error:function(){
                            swal("Error!", "There is an issue processing this change :( Please contact us.", "error");
                        }
                    }); //end of ajax
                });
        });
    </script>
@endsection
