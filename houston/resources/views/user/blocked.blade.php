@extends('layouts.app', ['title' => 'Blocked'])

@section('styles')
@endsection

@section('content')
        <!-- Main container -->
<main>
    <h4 style="padding: 100px;" class="text-center">
        <i style="font-size: 80px; margin-bottom: 20px; " class="fa fa-ban text-danger"></i><br>
        You Account Has Been Banned.
        <br>
        If you feel like you should not have been, <a href="{{ route('contact.show') }}">contact us.</a>
    </h4>
</main>
<!-- END Main container -->

@endsection

@section('scripts')
@endsection
