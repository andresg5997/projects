@extends('emails.layouts.app')

@section('content')

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br><br>

                <center>

                    <table style="margin: 0 auto" cellpadding="0" cellspacing="0" class="force-width-80">
                        <tr>
                            <td style="font-size:12px; text-align: center;">
                                <img width="90" height="90" src="https://www.filepicker.io/api/file/Dmnj8hKTwucmkwdgExw4" alt="User Profile"><br>
                                User:<a style="text-decoration:none;color:#f5774e;" href="#">Jon Doe</a>
                            </td>
                            <td class="mobile-resize" style="color:#159eee; font-size: 30px; font-weight: 600; padding-left:30px; text-align: left; vertical-align: top;">
                                Sign up now and receive a 20% discount!
                            </td>
                        </tr>
                    </table>

                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:left; color: #6f6f6f;">
                                <br>
                                I set up an Awesome Co profile where I can do tons of really awesome stuff. I want you to follow me so we can keep in touch!<br><br>
                                Because I invited you, you'll now receive 20% off when you sign up. See you soon!<br><br>
                                John Doe
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
                                   style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">Sign up!</a>
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
