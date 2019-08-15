@extends('emails.layouts.app')

@section('content')

{{--     @component('emails.components.content_head')
        @slot('heading')
            Check this out!
        @endslot

        @slot('body')
            Come check out my stuff on Awesome Co!
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent --}}

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br><br>

                <center>
                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:left; color: #6f6f6f;">
                                <br>
                                <p>Hello {{ $player->parent }},</p>
                                <p>A new <strong>{{ $team->name }}</strong> event, <b>{{ $event->name }}</b> has been created.</p>
                                <ul>
                                    <li>
                                        <b>Event:</b> {{ $event->name }}
                                    </li>
                                    <li>
                                        <b>Event Type:</b> {{ $event->type->name }}
                                    </li>
                                    <li>
                                        <b>Date/Time:</b> {{ date('D, M d, Y \a\t h:i A', strtotime($event->date)) }} - {{ date('h:i A', strtotime($event->end_date)) }}
                                    </li>
                                    <li>
                                        <b>Location:</b> <a href="{{ $event->location_url }}" title="">{{ $event->location_name }}</a>
                                    </li>
                                    @if($event->notes)
                                        <li>
                                            <b>Event Notes:</b> <span>{{ $event->notes }}</span>
                                        </li>
                                    @endif
                                </ul>
                                <p>You were sent this email because your child, {{ $player->name }} is registered in the team <b>{{ $team->name }}</b>.</p>
                                <br>
                                <p>
                                    Happy playing,<br><br>
                                    <b>{{ config('website_title') }}</b>
                                </p>
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
                                   style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;">
                                        @if(\App\User::where('email', $player->parent_email)->first())
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

            </td>
        </tr>
    </table>
@endsection
