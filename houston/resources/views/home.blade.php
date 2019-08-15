@extends('layouts.app', ['title' => config('main_title')])
@section('styles')
    <script>
        window.onbeforeunload = function() {
            $.ajax({
                // Query to server
                async: false,
                method: "POST",
                url: "{{ route('home.clear.session') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                }
            });
        }
    </script>

    <style>
    ins {
        border: none;
    }
    </style>
@endsection

@section('content')

<!-- Main container -->
<main>

    {{-- @include('components.page_links', ['view' => 'home']) --}}

    @include('components.media_items', ['view' => 'home'])

</main>
<!-- END Main container -->

@endsection

@section('scripts')

    @include('components.media_items_scripts')

@endsection