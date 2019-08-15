@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            New {{ $type }} - {{ $title }}
        @endslot

        @slot('body')
            Someone sent a new {{ $type }}.
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
                                Hi {{ config('website_title') }} Support,
                                <br><br>
                                You received a new {{ $type }} by {{ $name }}. It is,
                                <p>{{ $comment }}</p>
                                <br>
                                <p>If you want to reply immediately, email {{ $name }} at <a href="mailto:{{ $email }}?Subject=Response%20-%20{{ str_replace(' ', '%20', $title) }}" target="_top">{{ $email }}</a></p>
                                <br>
                                <a href="{{ url('admin/messages') }}">Check it out here.</a>
                                <br><br>
                                Thanks for being a loyal supporter,
                                <br>
                                Admin
                                <br>
                                <br>
                                <br>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
@endsection
