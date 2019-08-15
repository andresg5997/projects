@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            Update!
        @endslot

        @slot('body')
            {{ $user->username }} just uploaded a new {{ ($type != 'audio') ? $type : $type." file" }}.
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br>

                <center>

                    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
                        <tr>
                            <td style="text-align:left; color: #6f6f6f;">
                                <br>
                                Hi {{ $following_user->username }},
                                <br><br>
                                You are following {{ $user->username }} and s/he just uploaded a new {{ ($type != 'audio') ? $type : $type." file" }}: "{{ $title }}"
                                <br>
                                If you are curious about what it is, <a href="{{ url('/') }}/media/{{ $slug }}">check it out here</a>.
                                <br><br>
                                Thanks for being a loyal user,
                                <br>
                                {{ config('website_title') }} Support
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                    </table>
                </center>

                <table style="margin:0 auto;" cellspacing="0" cellpadding="10" width="100%">
                    <tr>
                        <td style="text-align:center; margin:0 auto;">
                            <br>
                            <div>
                                <!--[if mso]>
                                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('/') }}/media/{{ $slug }}" style="height:45px;v-text-anchor:middle;width:220px;" stroke="f" fillcolor="#f5774e">
                                    <w:anchorlock/>
                                    <center>
                                        <![endif]-->
                                        <a href="{{ url('/') }}/media/{{ $slug }}" style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">View the {{ $type }}</a>
                                        <!--[if mso]>
                                    </center>
                                </v:rect>
                                <![endif]-->
                            </div>
                            <br>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
