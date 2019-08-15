@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            Your opinion matters!
        @endslot

        @slot('body')
            Please take a moment to rate our services from 0 - 10 below.
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff; padding-top: 35px;">

                <center>

                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td class="pusher">&nbsp;</td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/luWd6n3rToOoqJfNUd2t" alt="0">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/Q9pMHTxlRvONXcqayo0V" alt="1">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/jy9Q2shMT5qvhzoObrJJ" alt="2">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/B9Q30sp5SAeaBFMmj9sY" alt="3">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/bJX78OhSs25zcLCsYdDA" alt="4">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/tz9f0zqORIqzOY0SeIa8" alt="5">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/iLHoOh1TUaXnrLmltFfN" alt="6">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/XdCYJMHzTWtvq9gLBluA" alt="7">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/vRrM4uTOSvK1BhVsTffi" alt="8">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/gbeWk6nYTnezATMdFVLC" alt="9">
                                </a>
                            </td>
                            <td>
                                <a href="">
                                    <img class="step" src="https://www.filepicker.io/api/file/JFjNOWscSfi8MWYRomTX" alt="10">
                                </a>
                            </td>
                            <td class="pusher">&nbsp;</td>
                        </tr>
                    </table>

                    <table style="margin:0 auto" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:center;">
                                (0 being unsatisfied - 10 being very satisfied)
                            </td>
                        </tr>
                    </table>

                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align: left; padding-bottom: 15px;">
                                <br>
                                Thank you for taking the time to let us know what you think. We will use this information to help improve our services!<br /><br />
                                Thank you, <br>
                                Awesome Inc
                                <br />
                                <br />
                            </td>
                        </tr>
                    </table>

                </center>

            </td>
        </tr>
    </table>
@endsection
