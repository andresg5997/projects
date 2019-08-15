@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            Update!
        @endslot

        @slot('body')
            Your file is added to your account.
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
                                Hi {{ $user->username }},
                                <br><br>
                                Your uploaded file {{ $original_name }} just finished uploading! <a href="{{ url('/') }}/media/{{ $new_file_name }}">Check it out here.</a>
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

            </td>
        </tr>
    </table>
@endsection
