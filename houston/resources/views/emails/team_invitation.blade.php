@extends('emails.layouts.app')

@php
    $check = (\App\User::where('email', $email)->first()) ? true : false;
@endphp

@section('content')
    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br><br>
                <center>
                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:center; color: #6f6f6f;">
                                <h3>
                                    Great News, {{ $name }}!
                                    <br>
                                    You've been added to <strong>{{ $team->name }}</strong> on {{ config('website_title') }}.
                                </h3>
                                @if($check)
                                    <p>Because you are already a {{ config('website_title') }} member, there's nothing you need to do - the next time you log in, your team will be there ready to go.</p>
                                    <p>
                                        Want to be a hero to your team manager? Take a minute to update your contact information.
                                    </p>
                                @else
                                    <p>
                                        You can now register at {{ config('website_title') }} and will be able to see <b>{{ $team->name }}</b> in your teams!
                                    </p>
                                @endif
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
                                    <a href="{{ ($check) ? route('login') : route('register') }}"
                                       style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">
                                            @if($check)
                                                Login!
                                            @else
                                                Sign up!
                                            @endif
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
