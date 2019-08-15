@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            Your invoice is ready
        @endslot

        @slot('body')
            Thanks so much for your business! Please review your invoice below.
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff; padding-top: 15px;">

                <center>
                    <table style="margin:0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:left;">
                                <br>
                                <b>Bob Erlicious</b> <br>
                                1234 Bobbz Way <br>
                                Victoria, BC <br>
                                V2A 7D8
                            </td>
                            <td style="text-align:right; vertical-align:top;">
                                <br>
                                <b>Invoice: 23130</b> <br>
                                2014-04-23
                            </td>
                        </tr>
                    </table>

                    <table style="margin:0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td class="mobile-block" >
                                <br>

                                <table cellspacing="0" cellpadding="0" class="force-full-width">
                                    <tr>
                                        <td style="border-bottom:1px solid #e3e3e3; font-weight: bold; text-align: left;">
                                            Description
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            Membership
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td  class="mobile-block">
                                <br>

                                <table cellspacing="0" cellpadding="0" class="force-full-width">
                                    <tr>
                                        <td style="border-bottom:1px solid #e3e3e3; font-weight: bold;">
                                            Period
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            August
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="mobile-block">
                                <br>

                                <table cellspacing="0" cellpadding="0" class="force-full-width">
                                    <tr>
                                        <td style="border-bottom:1px solid #e3e3e3; font-weight: bold;" class="mobile-align">
                                            Amount
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="mobile-align">
                                            $50.00
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>


                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align: left;">
                                <br>
                                The amount of <b>$50.00 CAD</b> has been charged on the credit card ending with 0123.
                                Please feel free to <a style="color: #f5774e; text-decoration: none;"href="#">contact us</a> with any questions regarding this invoice. <br><br>
                                Thank you, <br>
                                Awesome Inc
                            </td>
                        </tr>
                    </table>
                </center>

                <table style="margin:0 auto;" cellspacing="0" cellpadding="10" width="100%">
                    <tr>
                        <td style="text-align:center; margin:0 auto;">
                            <br>
                            <div><!--[if mso]>
                                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:45px;v-text-anchor:middle;width:180px;" stroke="f" fillcolor="#f5774e">
                                    <w:anchorlock/>
                                    <center>
                                <![endif]-->
                                <a href="http://"
                                   style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">My Account</a>
                                <!--[if mso]>
                                </center>
                                </v:rect>
                                <![endif]--></div>
                            <br>
                        </td>
                    </tr>
                </table>


                <table cellspacing="0" cellpadding="0" bgcolor="#363636"  class="force-full-width">
                    <tr>
                        <td style="background-color:#363636; text-align:center;">
                            <br>
                            <br>
                            <img width="62" height="56" img src="https://www.filepicker.io/api/file/FjkhDKXsTFyaHnXhhBCw">
                            <img width="68" height="56" src="https://www.filepicker.io/api/file/W6gXqm5BRL6qSvQRcI7u">
                            <img width="61" height="56" src="https://www.filepicker.io/api/file/eV9YfQkBTiaOu9PA9gxv">
                            <br>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#f0f0f0; font-size: 14px; text-align:center; padding-bottom:4px;">
                            © 2014 All Rights Reserved
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#27aa90; font-size: 14px; text-align:center;">
                            <a href="#">View in browser</a> | <a href="#">Contact</a> | <a href="#">Unsubscribe</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;">
                            &nbsp;
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
