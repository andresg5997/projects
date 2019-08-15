@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            We really miss you!
        @endslot

        @slot('body')
            And we want you back! Please give us another try.
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent

    <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">

                <table cellspacing="0" cellpadding="0" class="force-full-width">
                    <tr>
                        <td width="230" class="mobile-hide">
                            <table cellspacing="0" cellpadding="0" class="force-full-width">
                                <tr>
                                    <td background="https://www.filepicker.io/api/file/fgh8Lk8YRYitm1rM4iLz" bgcolor="#ffffff" width="230" height="113" valign="top" style="background-repeat:no-repeat; ">
                                        <!--[if gte mso 9]>
                                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:230px;height:113px;">
                                            <v:fill type="frame" src="https://www.filepicker.io/api/file/fgh8Lk8YRYitm1rM4iLz" color="#ffffff" />
                                            <v:textbox inset="0,0,0,0">
                                        <![endif]-->
                                        <div>
                                            <table cellspacing="0" cellpadding="0" class="force-full-width">
                                                <tr>
                                                    <td style="font-size: 45px; font-weight: 900; color: #ffffff; text-align:right; padding-top:10px; padding-right:40px; ">
                                                        20%
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!--[if gte mso 9]>
                                        </v:textbox>
                                        </v:rect>
                                        <![endif]-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="360" class="w320">
                            <table cellspacing="0" cellpadding="0" class="w280 w100p">
                                <tr>
                                    <td class="mobile-center activate-now" style="color:#159eee; font-size: 30px; font-weight: 600; line-height:30px; text-align:left;" >
                                        Discount on your next purchase!
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>


                <table cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td style="text-align:left;" class="mobile-center body-padding w320">
                            <br>
                            Awesome Co is continuing to grow and we miss seeing you be a part of it! We've increased store hours and have lot's of new brands available. To welcome you back please accept this 20% discount on you next purchase by clicking the button below.
                        </td>
                    </tr>
                </table>


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
                                   style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">Redeem Discount!</a>
                                <!--[if mso]>
                                </center>
                                </v:rect>
                                <![endif]--></div>
                            <br>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
