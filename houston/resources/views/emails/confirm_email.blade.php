@extends('emails.layouts.app')

@section('content')

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br><br>

                <center>


                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:left; color: #6f6f6f;">
                                <br>
                                <h1>{{ config('website_title') }} welcomes you!</h1>
                                <p>
                                    We are excited to have you in our community, with us you'll have all your sports in one hand, managing easily your teams.
                                </p>
                                <p>Please confirm your email to have full access!</p>
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
                                    <a href="{{ route('confirm.email', $user->token) }}"
                                       style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">
                                            Confirm your email!
                                       </a>
                                    <!--[if mso]>
                                    </center>
                                    </v:rect>
                                    <![endif]--></div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
@endsection